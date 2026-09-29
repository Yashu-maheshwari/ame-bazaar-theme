# System Architecture & Retail Daddy Blockers

## 1. Sync Timing & URL Predictability
**The Challenge:** 
When staff process a Purchase Entry in Retail Daddy and click "Print Label", it happens instantly. However, the RaintechSyncWorker.ps1 runs on a schedule (e.g., every 5-15 minutes). 
This means at the exact second the label is printed, the product *does not yet exist* on WooCommerce.

**The Solution:**
The shortlink URLs are 100% predictable: https://amebazaar.in/p/{ProductCode}.
The label can safely be printed immediately. If a customer attempts to scan the tag before the sync worker has executed, the AME Bazaar QR Shortlink plugin gracefully catches the missing SKU and redirects them to the shop search page. Once the sync script runs and pushes the product, subsequent scans will instantly redirect to the actual product page.

## 2. Retail Daddy 1.8 Printing Constraints (BLOCKER)
**The Constraint:**
Retail Daddy's native C# code strictly encodes the value of the Barcode text box into the Temp_Stock.QrBarcode image. This native logic cannot be changed without recompiling the executable.

**Proposed Integration Strategy:**
We are relying on **Crystal Reports** to bypass the native image generation. By ignoring Temp_Stock.QrBarcode and inserting a dynamic Formula Field in the .rpt file, Crystal Reports can theoretically render the URL QR natively.

**Current Blocker:**
While the CrystalDecisions DLLs present on the machine (May 2022) indicate support for native QR generation, **this cannot be confirmed via remote read-only inspection**. 
Crystal Reports capabilities depend heavily on the exact designer version and runtime patch levels installed.

**Required Manual Intervention:**
1. A technician must physically open C:\POS LATEST\CryReport\BarcodeT8.rpt in Crystal Reports Designer on the local machine.
2. The technician must attempt to right-click a formula field and select **"Change To Barcode" ➔ "QR Code"**.
3. If this option is missing or fails to render on the physical Zebra/TSC printer, the Crystal Reports integration is blocked.

**Fallback Strategy (If Crystal Reports Fails):**
If the POS runtime refuses to render a formula-based QR code, the safest alternative is to deploy a lightweight, standalone Python or C# utility (e.g., AME_Label_Printer.exe). This utility would:
1. Connect directly to the Raintech_DB1 database.
2. Read the latest Purchase Entry.
3. Generate exact Zebra Programming Language (ZPL) commands containing both the 1D barcode and the URL QR code.
4. Send the ZPL directly to the thermal printer, completely bypassing Crystal Reports.
