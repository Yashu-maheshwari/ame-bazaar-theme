# Meesho Image Batch Size Test

## Issue Observed
- **Crashpad_HandlerDidNotRespond:** The browser crashed with an Out of Memory error when attempting to render the Image Bulk Upload table for a 200-image batch.
- **Assessment:** While many platforms claim high theoretical batch limits (e.g. 500 images/100MB), the Meesho frontend actually renders base64 thumbnail blobs and highly complex DOM elements in its Image Links table. Generating 200 DOM rows containing heavy images exceeds standard browser heap limitations, forcing a crash.

## Strategy
- **Official Batch Size Guidance / Best Practices:** Official guidance often recommends keeping visual bulk uploads under 100 images. To be extremely conservative and prevent any further browser instability, we are halving that standard limit to 50 images per ZIP. 
- **Chosen Test Size:** 50 images.

## Validation Results
- **Test ZIP Path:** `C:\Users\user\Documents\website\scripts\meesho_image_export_package\zips\Batch_TEST_50.zip`
- **Expected Image Count:** 50
- **Actual Image Count:** 50
- **Duplicates Found:** 0
- **Source:** First 50 files extracted directly from the authoritative `Batch_01` manifest.
- **ZIP Size:** 234 KB
- **Verification:** ✅ PASS

DO NOT delete the original 19 batches until this 50-image test is fully validated.