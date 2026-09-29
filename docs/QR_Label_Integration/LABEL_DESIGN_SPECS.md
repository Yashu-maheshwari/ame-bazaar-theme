# AME Bazaar Smart Product Label Design Specifications

## 1. Physical Layout Requirements
* **Size:** Must conform to the active thermal label roll used by the Retail Daddy printer (typically 50mm x 25mm, or standard jewelry tags).
* **Quiet Zone:** The QR code MUST have at least a 4-module white border (quiet zone) around it. If placed too close to text or the edge of the sticker, mobile cameras will fail to focus.
* **Contrast:** Pure black ink on pure white thermal paper.

## 2. Content Placement (Top to Bottom / Left to Right)
1. **Header:** AME Bazaar Logo (or bold text AME BAZAAR) at the top center.
2. **Product Name:** Truncated to 2 lines maximum.
3. **Retail Daddy Barcode (1D):** 
   - Rendered using standard Code 128 or EAN-13 font.
   - Sourced from {Table1.Barcode}.
   - Must be large enough for the physical billing scanner to read at a distance of 10-15 cm.
4. **Customer QR Code (2D):**
   - Rendered as a square.
   - Sourced from the formula: "https://amebazaar.in/p/" + {Table1.ProductCode}
   - Minimum physical print size: 10mm x 10mm.
5. **Call to Action:** Small text under the QR code reading: *"Scan for product details"*.
6. **Price (Optional):** Only display the price if it is guaranteed to match the website. Sourced from {Table1.SellingPrice}.

## 3. Data Bindings (Crystal Reports)
* **1D Barcode:** Binds to Barcode column in BarcodePrint.xml dataset.
* **2D QR Code:** Binds to dynamic Formula Field @WebsiteQR -> "https://amebazaar.in/p/" + {Table1.ProductCode}.
* **Product Name:** Binds to ProductName.
* **SKU Text:** Binds to ProductCode.
