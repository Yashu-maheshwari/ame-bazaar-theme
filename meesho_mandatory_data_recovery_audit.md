# Meesho Mandatory Data Recovery Audit

I performed a deep, read-only inspection of WooCommerce (REST API), Raintech (`Product` table), and the AME Bazaar codebase to recover the mandatory template fields missing from our initial mapping.

## 1. Fields Recoverable Automatically
✅ **MRP (Max Retail Price)**: Successfully found in Raintech `Product` table (Column: `MRP`). Example: P-3616 has SellingPrice `450.00` and MRP `795.00`. This completely solves the strict `Meesho Price < MRP` rule without inventing prices!
✅ **Country of Origin**: Available in AME Bazaar codebase (`schema.php` as `_ame_country_of_origin`). Defaults to `India`.
✅ **Manufacturer Name**: Available in AME Bazaar codebase (`schema.php` as `_ame_manufacturer`). Defaults to `Apparel Maheshwari Enterprises`.
✅ **Generic Name**: Automatable via the Marketplace Engine Category mappings (e.g. `T-shirt`).
✅ **Net Quantity**: Standardized to `1` for individual products.
✅ **Meesho Price / Selling Price**: Available in WooCommerce and Raintech.
✅ **SKU / ProductCode**: Available in WooCommerce and Raintech.

## 2. Fields Requiring Business-Level Configuration (Configure Once)
The following mandatory compliance fields are NOT configured anywhere in the current systems but can be provided *once* by the business and automatically applied to all products:
- Manufacturer Address & Pincode
- Packer Name, Address, & Pincode (Usually identical to Manufacturer)
- Importer Name, Address, & Pincode (If applicable, else N/A or local config)

**Architecture Recommendation**: Do not clutter WooCommerce master data with these static marketplace compliance values. Store them in `scripts/.env` or a lightweight `meesho-business-profile.json` so they seamlessly inject into the template builder.

## 3. Fields Requiring Genuine Product-Level Manual Data
The Raintech POS system and WooCommerce instances are extremely barebones. There are absolutely zero records of colors, sizes, or fabrics for these products in the database.
- Color
- Fabric
- Fit/Shape
- Neck
- Pattern
- Print Or Pattern Type
- Sleeve Length
- Chest Size (Inch)
- Length Size (Inch)
- Shoulder Size (Inch)

**Architecture Recommendation**: Because WooCommerce master data should not be polluted with Meesho-specific rigid constraints, we should build a local lightweight UI extension to the `review-tool.js` (or a spreadsheet workflow) that allows rapid, bulk manual assignment of these missing attributes mapping strictly to the SKUs.

## 4. Official Image Workflow Findings
I investigated the template's instruction to *"Click on Images Bulk Upload on the supplier panel"*.
- **Workflow**: Meesho actively blocks random internet URLs (including WooCommerce or Google Drive links) to enforce quality control and prevent hotlinking.
- **Human Action Required**: A user MUST log into the secure Meesho Supplier Panel, upload the genuine Raintech images (which we have safely stored on your local disk), and download the resulting Image Link mapping provided by Meesho.
- **Automatable Component**: Once the human provides the mapping (e.g., an Excel export from Meesho), our local script can automatically join the generated Meesho Image URLs to our WooCommerce SKUs and inject them into the final bulk template seamlessly.

## 5. The Exact Remaining Blockers
Before we can generate a perfectly valid, upload-ready Meesho T-shirt CSV, we need:
1. **Business Profile Config**: Provide the AME Bazaar Manufacturer Address/Pincode.
2. **Product Attributes**: Manual data entry for Color, Fabric, Size, etc. for the target products.
3. **Image Link Generation**: Human upload of the images to the Supplier panel to acquire the accepted Meesho URLs.
