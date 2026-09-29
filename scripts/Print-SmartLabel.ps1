<#
.SYNOPSIS
Standalone Smart Label Printer for AME Bazaar.
.DESCRIPTION
Fallback utility to print a smart product label containing both the 1D staff barcode 
and the 2D customer QR code.
.PARAMETER ProductCode
The SKU of the product to query and print (e.g., P-3660).
.PARAMETER Print
Switch to actually print physically. If omitted, only saves a preview image.
.PARAMETER PrinterName
The name of the thermal printer. Defaults to "Xprinter XP-470B".
.PARAMETER LabelWidthMm
Width of the label in millimeters. Defaults to 50.
.PARAMETER LabelHeightMm
Height of the label in millimeters. Defaults to 25.
#>
param(
    [Parameter(Mandatory=$true)]
    [string]$ProductCode,
    [switch]$Print,
    [string]$PrinterName = "Xprinter XP-470B",
    [int]$LabelWidthMm = 50,
    [int]$LabelHeightMm = 25
)

Write-Host "Validating Printer: $PrinterName"
$printer = Get-Printer -Name $PrinterName -ErrorAction SilentlyContinue
if (-not $printer) {
    Write-Error "Printer '$PrinterName' not found on this system. Cannot proceed."
    exit 1
}
Write-Host "Printer found: $($printer.DriverName)"

Write-Host "Querying database for ProductCode: $ProductCode"
$dbConnString = "Server=localhost\MSSQLSERVERPOS2;Database=Raintech_DB1;Integrated Security=True;"
$conn = New-Object System.Data.SqlClient.SqlConnection($dbConnString)
$conn.Open()
$cmd = $conn.CreateCommand()
# Use Product table for canonical data
$cmd.CommandText = "SELECT TOP 1 ProductCode, ProductName, Barcode, SellingPrice FROM Product WHERE ProductCode = @pc"
$cmd.Parameters.AddWithValue("@pc", $ProductCode) | Out-Null
$reader = $cmd.ExecuteReader()

if (-not $reader.Read()) {
    Write-Error "ProductCode '$ProductCode' not found in Raintech_DB1.Product table."
    $conn.Close()
    exit 1
}

$dbProductCode = $reader["ProductCode"].ToString().Trim()
$dbProductName = $reader["ProductName"].ToString().Trim()
$dbBarcode = $reader["Barcode"].ToString().Trim()
$dbSellingPrice = $reader["SellingPrice"].ToString().Trim()
$conn.Close()

if (-not $dbBarcode) {
    Write-Error "Product '$dbProductName' is missing a 1D Barcode. Cannot generate label."
    exit 1
}

Write-Host "Found Product: $dbProductName (Barcode: $dbBarcode)"

Add-Type -AssemblyName System.Drawing
try {
    Add-Type -Path "C:\POS LATEST\QRCoder.dll"
    Add-Type -Path "C:\POS LATEST\Zen.Barcode.Core.dll"
} catch {
    Write-Error "Required POS barcode DLLs not found in C:\POS LATEST\"
    exit 1
}

# Assume 203 DPI
$dpi = 203
$widthPx = [int]($LabelWidthMm / 25.4 * $dpi)
$heightPx = [int]($LabelHeightMm / 25.4 * $dpi)

$bmp = New-Object System.Drawing.Bitmap($widthPx, $heightPx)
$bmp.SetResolution($dpi, $dpi)
$graphics = [System.Drawing.Graphics]::FromImage($bmp)
$graphics.Clear([System.Drawing.Color]::White)
$graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::None
$graphics.TextRenderingHint = [System.Drawing.Text.TextRenderingHint]::SingleBitPerPixelGridFit

# Fonts
$fontHeader = New-Object System.Drawing.Font("Arial", 8, [System.Drawing.FontStyle]::Bold)
$fontName = New-Object System.Drawing.Font("Arial", 9, [System.Drawing.FontStyle]::Bold)
$fontSmall = New-Object System.Drawing.Font("Arial", 7, [System.Drawing.FontStyle]::Regular)
$brush = [System.Drawing.Brushes]::Black

# 1. Header (AME BAZAAR)
$graphics.DrawString("AME BAZAAR", $fontHeader, $brush, 10, 5)

# 2. Draw Product Name
# Truncate if too long (very basic approach for script)
$displayAppName = $dbProductName
if ($displayAppName.Length -gt 25) { $displayAppName = $displayAppName.Substring(0, 25) + "..." }
$graphics.DrawString($displayAppName, $fontName, $brush, 10, 22)

# 3. Draw 1D Barcode (Middle Left) using Barcode (NOT ProductCode)
try {
    $barcodeFactory = [Zen.Barcode.BarcodeDrawFactory]::Code128WithChecksum
    $barcodeImage = $barcodeFactory.Draw($dbBarcode, 35, 2)
    $graphics.DrawImage($barcodeImage, 10, 40)
    $graphics.DrawString($dbBarcode, $fontSmall, $brush, 10, 78)
} catch {
    Write-Error "Failed to generate 1D Barcode: $_"
    exit 1
}

# 4. Generate and Draw 2D Customer QR (Right Side)
try {
    $qrUrl = "https://amebazaar.in/p/$dbProductCode"
    $qrGenerator = New-Object QRCoder.QRCodeGenerator
    $qrData = $qrGenerator.CreateQrCode($qrUrl, [QRCoder.QRCodeGenerator+ECCLevel]::Q)
    $qrCode = New-Object QRCoder.QRCode($qrData)
    $qrImage = $qrCode.GetGraphic(3)
    
    # Place QR on the right, keeping quiet zone safe
    $qrX = $widthPx - $qrImage.Width - 15
    $graphics.DrawImage($qrImage, $qrX, 10)
    $graphics.DrawString("Scan for", $fontSmall, $brush, $qrX + 5, 10 + $qrImage.Height)
    $graphics.DrawString("Details", $fontSmall, $brush, $qrX + 5, 20 + $qrImage.Height)
} catch {
    Write-Error "Failed to generate 2D QR Code: $_"
    exit 1
}

# Optional Price
if ($dbSellingPrice) {
    $graphics.DrawString("Rs. $dbSellingPrice", $fontHeader, $brush, 10, 95)
}

$graphics.Dispose()

$previewPath = "$PSScriptRoot\label_preview_$dbProductCode.png"
$bmp.Save($previewPath, [System.Drawing.Imaging.ImageFormat]::Png)

Write-Host "Label generated successfully at $previewPath"

if ($Print) {
    Write-Host "Initiating physical print to $PrinterName..."
    $pd = New-Object System.Drawing.Printing.PrintDocument
    $pd.PrinterSettings.PrinterName = $PrinterName
    
    # Set paper size in hundredths of an inch
    $widthHundredths = [int]($LabelWidthMm / 25.4 * 100)
    $heightHundredths = [int]($LabelHeightMm / 25.4 * 100)
    $pd.DefaultPageSettings.PaperSize = New-Object System.Drawing.Printing.PaperSize("Custom", $widthHundredths, $heightHundredths)
    $pd.DefaultPageSettings.Margins = New-Object System.Drawing.Printing.Margins(0,0,0,0)

    $pd.add_PrintPage({
        param($sender, $e)
        $e.Graphics.DrawImage($bmp, 0, 0)
    })
    
    try {
        $pd.Print()
        Write-Host "Print job sent successfully."
    } catch {
        Write-Error "Failed to send print job: $_"
    }
} else {
    Write-Host "Running in PREVIEW mode. Label was not physically printed."
    Write-Host "To print, run the script with the -Print switch."
}

$bmp.Dispose()

