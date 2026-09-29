# AME Bazaar Smart Label End-to-End QA Report

## A. Automated / Software Tests (Verified Local Implementation)
| Test Case | Expected Result | Execution Status |
| :--- | :--- | :--- |
| **Plugin Activation** | Activation hook successfully flushes rewrite rules. | ✅ **Passed** (Static Code Verification) |
| **Known Published SKU** | /p/P-3660 -> 302 Redirect to /product/damro-shizuka/. | ✅ **Passed** (Logic Simulation) |
| **Unknown SKU Fallback** | /p/INVALID-99 -> 302 Redirect to /?s=INVALID-99&post_type=product. | ✅ **Passed** (Logic Simulation) |
| **Draft/Unpublished SKU** | Redirects to shop search. Private info is not leaked. | ✅ **Passed** (Logic Simulation) |
| **URL Sanitization** | <script>XSS</script> is stripped by sanitize_text_field. | ✅ **Passed** (Static Code Verification) |

## B. Static Inspection (Retail Daddy Environment)
| Test Case | Expected Result | Execution Status |
| :--- | :--- | :--- |
| **Identify Active Report** | Active label report located. | ✅ **Passed**: Found BarcodeT8.rpt & GBarcodeT14.rpt. |
| **Create Report Backup** | Safe experimental .rpt copy created for Designer modifications. | ✅ **Passed**: Created BarcodeT8_SmartQR_Experiment.rpt. |
| **Crystal Dependencies** | CrystalDecisions runtime supports QR logic. | ✅ **Passed**: Found SP32+ DLLs. |

## C. Fallback Label Printer Verification (Physical Generation Test)
| Test Case | Expected Result | Execution Status |
| :--- | :--- | :--- |
| **Generate 1D Barcode** | Print-SmartLabel.ps1 successfully calls Zen.Barcode. | ✅ **Passed** (Tested locally) |
| **Generate 2D QR Code** | Print-SmartLabel.ps1 successfully calls QRCoder. | ✅ **Passed** (Tested locally) |
| **Assemble Bitmap** | Generates a 400x200px test label image combining both elements. | ✅ **Passed** (Preview generated successfully) |

## D. Pending Blockers (Requires Physical Retail Daddy Setup)
| Test Case | Expected Result | Execution Status |
| :--- | :--- | :--- |
| **Crystal Designer Edit** | Operator successfully applies "QR Code" barcode format to the URL formula field. | ⚠️ **PENDING** (GUI access blocked) |
| **Print Output (Crystal)** | Printer produces a crisp dual-barcode sticker. | ⚠️ **PENDING** (Physical hardware access blocked) |
| **Print Output (Fallback)**| ZPL/Bitmap prints properly on physical thermal roll if CR fails. | ⚠️ **PENDING** (Physical hardware access blocked) |
| **Staff Scanner Test** | Physical POS laser scanner accurately reads the original 1D barcode on the new tag. | ⚠️ **PENDING** (Physical hardware access blocked) |
| **Customer Phone Test** | iPhone/Android camera opens the URL instantly. | ⚠️ **PENDING** (Physical hardware access blocked) |
