# Meesho Batch Automation Opportunities

We analyzed the official Meesho Excel templates to find structural mechanisms to reduce the 2,665-product manual entry burden. 

## 1. Product ID / Group ID Level Batching
**Opportunity:** The templates utilize Product ID / Style ID to group variations.
**How it saves time:** 
Instead of manually filling 4,626 distinct rows, we can establish **Product Profiles**. A human only needs to define the attributes (Fabric, Pattern, Image 1) ONCE per Style ID. The automated script can then aggressively copy those values down to every size variation (S, M, L, XL) of that same Style ID.

## 2. Standardized Measurement Profiles (Size Charts)
**Opportunity:** Measurements are strictly enforced (e.g., Chest Size, Length Size in Inches) and must be Dropdown-compliant.
**How it saves time:**
Instead of typing 38, 26, 18 for every single medium T-shirt, we can create standard Size Chart JSON profiles (e.g., M_TSHIRT_STD). When the human reviewer assigns the M_TSHIRT_STD profile to a batch of products, the engine automatically populates the exact required columns for Chest, Length, and Shoulder across the entire batch.

## 3. Top/Bottom Split Reusability
**Opportunity:** Kids Clothing Sets require distinct Top Fabric and Bottom Fabric.
**How it saves time:**
Since AME Bazaar predominantly uses Cotton blends for Sets, we can establish a catalog-level default fallback to Cotton Blend for both Top and Bottom, surfacing it in the UI only for quick confirmation rather than manual selection from the 50+ dropdown list.

## 4. Bulk Image Handling limitation
**Blocker:** Meesho expressly forbids Google Drive and standard WooCommerce media URLs in the Excel template.
**Mitigation:** 
We must use the official Meesho Image Link Generator. However, we can bulk upload images using the exact WooCommerce SKU as the filename (e.g., P-3652.jpg). After Meesho generates the links, we can export them. We will then build a simple node script to auto-map the returned meeshosupply.com links back to the Excel template matching by SKU.
