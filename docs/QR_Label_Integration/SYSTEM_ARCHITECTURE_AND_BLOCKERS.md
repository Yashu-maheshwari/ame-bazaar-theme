# System Architecture & Retail Daddy Blockers

## 1. Sync Timing & URL Predictability
**The Challenge:** 
Labels are printed instantly during Purchase Entry. The WooCommerce sync runs asynchronously on a schedule. At print time, the product may not exist on WooCommerce.

**The Solution:**
The shortlink URLs are completely predictable: https://amebazaar.in/p/{ProductCode}.
The plugin handles missing SKUs by safely redirecting customers to the AME Bazaar search page (/?s={SKU}&post_type=product). Once the sync completes, the exact same URL automatically redirects to the published product page.

## 2. Retail Daddy 1.8 Printing Constraints (BLOCKER)
**The Constraint:**
Retail Daddy natively couples Product.Barcode directly to Temp_Stock.QrBarcode. Modifying the C# executable is high-risk. 

**Crystal Reports Approach:**
We rely on inserting a dynamic formula field ("https://amebazaar.in/p/" + {Table1.ProductCode}) into the active BarcodeT8.rpt. 

**The Pending Blocker:**
We cannot remotely automate or verify Crystal Reports Designer modifying the .rpt structure. This requires a human operator to physically open the designer, add the field, and test a physical Zebra/TSC printer printout. An experimental safe copy has been generated (BarcodeT8_SmartQR_Experiment.rpt).

## 3. The Fallback Label Printer Utility (Phase 4)
If Crystal Reports fails to render the dual-barcode layout, we have implemented a **Standalone PowerShell Label Printer** (scripts/Print-SmartLabel.ps1).

* **How it works:** It uses the existing Zen.Barcode.Core.dll and QRCoder.dll libraries already present in the Retail Daddy installation directory (C:\POS LATEST\).
* **Capabilities:** It programmatically draws a 50x25mm (400x200px) thermal label bitmap containing the product name, 1D staff billing barcode on the left, and the 2D customer web QR code on the right. 
* **Safety:** It communicates directly with the Windows Spooler (System.Drawing.Printing.PrintDocument) completely bypassing Crystal Reports and Retail Daddy executable constraints. It is strictly read-only and does not modify the Retail Daddy database.
