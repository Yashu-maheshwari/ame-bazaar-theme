######################################################################
# Raintech BLOB Extractor — READ from SQL Server, WRITE to temp files
# Uses .NET SqlConnection (shared memory, same as sqlcmd)
# Does NOT modify Raintech database
######################################################################

param(
    [int]$Limit = 0  # 0 = all, >0 = limit for test batch
)

$ErrorActionPreference = "Stop"

# Load credentials from .env
$envFile = Join-Path $PSScriptRoot ".env"
if (-not (Test-Path $envFile)) {
    Write-Error ".env file not found at $envFile"
    exit 1
}
Get-Content $envFile | ForEach-Object {
    if ($_ -match '^\s*([^#][^=]+)=(.*)$') {
        [Environment]::SetEnvironmentVariable($Matches[1].Trim(), $Matches[2].Trim(), "Process")
    }
}

$dbServer   = $env:DB_SERVER
$dbName     = $env:DB_NAME
$dbUser     = $env:DB_USER
$dbPassword = $env:DB_PASSWORD

if (-not $dbServer -or -not $dbName) {
    Write-Error "DB_SERVER or DB_NAME not set in .env"
    exit 1
}

# Output directory for extracted images
$outDir = Join-Path $PSScriptRoot "extracted_images"
if (-not (Test-Path $outDir)) {
    New-Item -ItemType Directory -Path $outDir -Force | Out-Null
}

# Build connection string
$connStr = "Server=$dbServer;Database=$dbName;User Id=$dbUser;Password=$dbPassword;"

Write-Host "Connecting to Raintech DB..."
$conn = New-Object System.Data.SqlClient.SqlConnection($connStr)
$conn.Open()
Write-Host "Connected."

# Query: get ProductCode + Photo BLOB
$topClause = ""
if ($Limit -gt 0) { $topClause = "TOP $Limit" }

$query = @"
SELECT $topClause p.ProductCode, p.ProductName, pj.Photo
FROM Product p
INNER JOIN Product_Join pj ON p.PID = pj.ProductID
WHERE pj.Photo IS NOT NULL
ORDER BY p.PID ASC
"@

$cmd = New-Object System.Data.SqlClient.SqlCommand($query, $conn)
$cmd.CommandTimeout = 300
$reader = $cmd.ExecuteReader()

$extracted = 0
$skipped   = 0
$invalid   = 0
$manifest  = @()

while ($reader.Read()) {
    $code = $reader["ProductCode"].ToString().Trim()
    $name = $reader["ProductName"].ToString().Trim()
    $blob = [byte[]]$reader["Photo"]

    if ($blob.Length -lt 100) {
        Write-Host "[SKIP] $code - BLOB too small ($($blob.Length) bytes)"
        $invalid++
        continue
    }

    # Detect MIME type from magic bytes
    $ext = "jpg"
    if ($blob[0] -eq 0xFF -and $blob[1] -eq 0xD8) { $ext = "jpg" }
    elseif ($blob[0] -eq 0x89 -and $blob[1] -eq 0x50) { $ext = "png" }
    elseif ($blob[0] -eq 0x47 -and $blob[1] -eq 0x49) { $ext = "gif" }
    elseif ($blob[0] -eq 0x42 -and $blob[1] -eq 0x4D) { $ext = "bmp" }

    $safeCode = $code -replace '[^a-zA-Z0-9_-]', '_'
    $filename = "${safeCode}.${ext}"
    $filepath = Join-Path $outDir $filename

    # Skip if already extracted (idempotent)
    if (Test-Path $filepath) {
        $skipped++
        $manifest += [PSCustomObject]@{
            ProductCode = $code
            ProductName = $name
            Filename    = $filename
            Size        = (Get-Item $filepath).Length
            Status      = "already_extracted"
        }
        continue
    }

    [System.IO.File]::WriteAllBytes($filepath, $blob)
    $extracted++
    $manifest += [PSCustomObject]@{
        ProductCode = $code
        ProductName = $name
        Filename    = $filename
        Size        = $blob.Length
        Status      = "extracted"
    }

    if ($extracted % 100 -eq 0) {
        Write-Host "Extracted $extracted images..."
    }
}

$reader.Close()
$conn.Close()

# Write manifest JSON for Node.js to consume
$manifestPath = Join-Path $PSScriptRoot "extracted_manifest.json"
$manifest | ConvertTo-Json -Depth 3 | Out-File -Encoding utf8 $manifestPath

Write-Host ""
Write-Host "=== EXTRACTION COMPLETE ==="
Write-Host "Extracted:        $extracted"
Write-Host "Already existed:  $skipped"
Write-Host "Invalid BLOBs:    $invalid"
Write-Host "Total in manifest: $($manifest.Count)"
Write-Host "Output dir:       $outDir"
Write-Host "Manifest:         $manifestPath"
