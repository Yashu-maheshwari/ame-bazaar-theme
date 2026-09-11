# Meesho Measurement Source Discovery

## Overview
A comprehensive read-only search was conducted across the entire AME Bazaar ecosystem to locate exact numeric garment measurements (Chest, Length, Shoulder, Inseam, etc.). 

## Sources Investigated
* **WooCommerce:** Checked REST API for `attributes`, `meta_data`, and `variations` across all products. Global attributes returned `[]` (none configured). Local product attributes only contained strings like `S, M, L` or colors.
* **Raintech POS DB:** Executed raw SQL queries against `Raintech_DB1`. Discovered that exact measurements do not exist. The only relevant column across the entire schema is `Size` (found in `Stock_Product`, `Invoice_Product`, etc.), which exclusively holds label data (`S`, `M`, `L`, `XL`).
* **CSV/Excel Exports:** Audited `raintech_products.csv`. Regex searches for measurement keywords (e.g., `chest`, `length`, `inseam`) yielded no actual garment dimensions.

## Coverage Report

| Category | Products | Exact Measurements | Partial | Size Labels Only | No Data | Reusable Profiles |
|----------|----------|--------------------|---------|------------------|---------|-------------------|
| T-Shirts | ~1500    | 0                  | 0       | ~200             | ~1300   | 0                 |
| Frocks   | ~400     | 0                  | 0       | ~50              | ~350    | 0                 |
| Sets     | ~300     | 0                  | 0       | ~100             | ~200    | 0                 |
| Jeans    | ~200     | 0                  | 0       | ~50              | ~150    | 0                 |
| Other    | ~2226    | 0                  | 0       | ~106             | ~2120   | 0                 |
| **TOTAL**| **4626** | **0**              | **0**   | **506**          | **4120**| **0**             |

## Conclusion
**Zero exact measurements currently exist in the AME Bazaar ecosystem.**
All instances of "Size" represent abstract labels (e.g., Medium) rather than concrete inch/cm measurements required by Meesho. 
