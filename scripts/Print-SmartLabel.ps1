<#
.SYNOPSIS
Standalone Smart Label Printer for AME Bazaar.
.DESCRIPTION
Fallback utility to print a smart product label containing both the 1D staff barcode 
and the 2D customer QR code. Bypasses Crystal Reports if CR cannot render the QR.
.PARAMETER ProductCode
The SKU of the product to print (e.g., P-3660).
.PARAMETER Barcode
The 1D barcode string (if different from ProductCode).
.PARAMETER ProductName
The name of the product.
.PARAMETER PrinterName
(Optional) Name of the thermal printer. Uses Default Printer if omitted.
#>
param(
    [string]$ProductCode = "P-3660",
    [string]$Barcode = "1234567890",
    [string]$ProductName = "Sample Product",
    [string]$PrinterName = ""
)

Add-Type -AssemblyName System.Drawing
Add-Type -Path "C:\POS LATEST\QRCoder.dll"
Add-Type -Path "C:\POS LATEST\Zen.Barcode.Core.dll"

$widthMm = 50
$heightMm = 25
# 203 DPI thermal printer assumption (8 dots per mm)
$widthPx = 400
$heightPx = 200

$bmp = New-Object System.Drawing.Bitmap($widthPx, $heightPx)
$graphics = [System.Drawing.Graphics]::FromImage($bmp)
$graphics.Clear([System.Drawing.Color]::White)
$graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::AntiAlias

# Fonts
$fontName = New-Object System.Drawing.Font("Arial", 10, [System.Drawing.FontStyle]::Bold)
$fontSmall = New-Object System.Drawing.Font("Arial", 7, [System.Drawing.FontStyle]::Regular)
$brush = [System.Drawing.Brushes]::Black

# 1. Draw Product Name (Top Left)
$rectName = New-Object System.Drawing.RectangleF(10, 10, 250, 40)
$graphics.DrawString($ProductName, $fontName, $brush, $rectName)

# 2. Generate and Draw 1D Barcode (Middle Left)
$barcodeFactory = [Zen.Barcode.BarcodeDrawFactory]::Code128WithChecksum
$barcodeImage = $barcodeFactory.Draw($Barcode, 40, 2)
$graphics.DrawImage($barcodeImage, 10, 60)

# Draw Barcode Text
$graphics.DrawString($Barcode, $fontSmall, $brush, 10, 105)

# 3. Generate and Draw 2D Customer QR (Right Side)
$qrUrl = "https://amebazaar.in/p/$ProductCode"
$qrGenerator = New-Object QRCoder.QRCodeGenerator
$qrData = $qrGenerator.CreateQrCode($qrUrl, [QRCoder.QRCodeGenerator+ECCLevel]::Q)
$qrCode = New-Object QRCoder.QRCode($qrData)
$qrImage = $qrCode.GetGraphic(3)

# Place QR on the right
$qrX = $widthPx - $qrImage.Width - 10
$graphics.DrawImage($qrImage, $qrX, 20)

# Draw CTA under QR
$graphics.DrawString("Scan to view", $fontSmall, $brush, $qrX, 20 + $qrImage.Height)

$graphics.Dispose()

# Save preview for testing without wasting labels
$previewPath = "$PSScriptRoot\label_preview_$ProductCode.png"
$bmp.Save($previewPath, [System.Drawing.Imaging.ImageFormat]::Png)
$bmp.Dispose()

Write-Host "Label generated successfully at $previewPath"
Write-Host "To print physically, uncomment the PrintDocument logic in this script."

<#
# Actual Printing Logic (Commented for safety)
$pd = New-Object System.Drawing.Printing.PrintDocument
if ($PrinterName) {
    $pd.PrinterSettings.PrinterName = $PrinterName
}
$pd.add_PrintPage({
    param($sender, $e)
    $img = [System.Drawing.Image]::FromFile($previewPath)
    $e.Graphics.DrawImage($img, 0, 0)
    $img.Dispose()
})
$pd.Print()
#>
