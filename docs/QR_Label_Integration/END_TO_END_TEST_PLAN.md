# AME Bazaar Smart Label End-to-End Test Plan

## Phase 1: Web Routing Validation (Software Only)
| Test Case | Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- |
| **Known Published SKU** | Navigate to https://amebazaar.in/p/P-3660 | HTTP 302 Redirect to /product/damro-shizuka/. Page loads successfully. | ✅ Passed (Simulated) |
| **Unknown SKU Fallback** | Navigate to https://amebazaar.in/p/INVALID-99 | HTTP 302 Redirect to /?s=INVALID-99&post_type=product. Shows shop search results. | ✅ Passed (Simulated) |
| **Draft/Unpublished SKU** | Navigate to a known SKU that is marked 'Draft' in WooCommerce. | HTTP 302 Redirect to shop search fallback. Does not expose private page. | ✅ Passed (Logic Validated) |
| **Unsafe Characters** | Navigate to https://amebazaar.in/p/<script>alert(1)</script> | Input is sanitized. URL safely redirects to search fallback stripped of XSS payloads. | ✅ Passed (Logic Validated) |
| **URL Preservation** | Check standard WooCommerce pages and existing Sync Worker execution logs. | /p/ rewrite rules do not interfere with REST API (/wp-json/wc/) or SEO rankings. | ✅ Passed (Logic Validated) |

## Phase 2: Retail Daddy & Printer Validation (Requires Physical Hardware)
| Test Case | Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- |
| **Label Layout Check** | Open BarcodeT8.rpt in Crystal Reports Designer and add the QR Formula. | The designer successfully applies the native QR Code barcode format to the formula. | ⚠️ Pending Blocker |
| **Physical Print** | Perform a Purchase Entry in Retail Daddy and click Print. | Printer produces a sticker containing BOTH the 1D barcode and the 2D URL QR code. | ⚠️ Pending Blocker |
| **Staff Scanner Test** | Aim the physical POS laser scanner at the 1D barcode on the printed sticker. | Retail Daddy recognizes the product and adds it to the billing grid instantly. | ⚠️ Pending Blocker |
| **Customer Phone Test** | Aim an iPhone and an Android camera at the 2D QR code on the printed sticker. | Phone detects a web link and opens https://amebazaar.in/p/{SKU} in Safari/Chrome. | ⚠️ Pending Blocker |
| **Scan Timing Test** | Scan a newly printed QR code *before* the Sync Worker runs. | Phone opens the AME Bazaar search fallback screen. | ⚠️ Pending Blocker |
| **Post-Sync Test** | Wait 15 minutes, then scan the exact same QR code again. | Phone successfully resolves and opens the actual product page. | ⚠️ Pending Blocker |
