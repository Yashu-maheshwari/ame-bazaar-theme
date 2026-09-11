# Meesho Category-Wise Feasibility Audit

## Current Catalog Context
- **Total Products:** 4,626
- **Marketplace Engine Ready:** 2,665
- **Official Templates Available Locally:** 1 (`Tshirts-10000-EXTERNAL-MeeshoTemplate2PricesENROLMENT.xlsx`)

## A. Which categories can be automated almost completely?
**NONE.** All apparel categories on Meesho have strict compliance, specific material attributes, and unique measurement requirements. Additionally, all image links must be generated through the Meesho Supplier Panel. Standard URL links are blocked.

## B. Which categories can be automated partially?
**T-Shirts (Men/Women/Kids).**
- We can completely automate: Pricing (using Raintech MRP/SellingPrice), Inventory (WooCommerce), Business Compliance (Manufacturer/Packer info), Country of Origin, and Net Quantity.
- **Estimated Automation:** ~40% of the required data.

## C. Which categories are blocked by exact measurements?
**All T-Shirts** (and likely all other apparel). The official T-shirts template strictly mandates exact inch measurements for `Chest Size`, `Length Size`, and `Shoulder Size` for every variation. These cannot be safely derived from images or generic data.

## D. Which categories are blocked by product-specific attributes?
**All Categories.** Fields like `Fabric`, `Color`, `Neck`, and `Pattern` are mandatory for T-Shirts and absent from our source data. Visual derivation is unsafe for Fabric.

## E. Which categories are blocked only by Meesho image-link generation?
**None.** Even if the image link generation workflow is resolved (via human bulk upload to the panel), the products remain blocked by missing attribute and measurement data.

## F. How many of the 2,665 currently ready products can realistically be prepared automatically?
**ZERO.** Without human input for measurements and material attributes, and without official Meesho templates for the non-Tshirt categories, we cannot safely generate a single 100% compliant upload row.

## G. What is the minimum human work still required?
1. Download official templates for the remaining mapped categories from the Meesho Supplier Panel.
2. Use the local Attribute Review tool to batch-assign Fabric, Color, and Sizes to the T-shirt cluster.
3. Upload the 3,668 recovered Raintech images to Meesho's Image Generator and export the generated URLs.

## H. What should we automate next to reduce that human work?
1. Expand the local Attribute Review Tool to support the other categories once their templates are downloaded.
2. Add a robust "Batch Apply" UI to the review tool so a human can select 50 identical T-shirts and apply "Cotton, Blue, Round Neck, M: 38/26/18" in one click.
