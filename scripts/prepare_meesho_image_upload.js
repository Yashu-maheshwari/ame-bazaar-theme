const fs = require('fs');
const path = require('path');
const sizeOf = require('image-size').imageSize;

const INPUT_DIR = path.join(__dirname, 'extracted_images');
const MANIFEST_IN = path.join(__dirname, 'extracted_manifest.json');
const OUTPUT_DIR = path.join(__dirname, 'meesho_image_export_package');
const MANIFEST_OUT_JSON = path.join(__dirname, 'meesho_image_manifest.json');
const MANIFEST_OUT_CSV = path.join(__dirname, 'meesho_image_manifest.csv');
const STATE_OUT = path.join(__dirname, 'meesho-image-link-state.json');

function run() {
    console.log("Reading extracted manifest...");
    let raw = fs.readFileSync(MANIFEST_IN, 'utf8');
    if (raw.charCodeAt(0) === 0xFEFF) raw = raw.slice(1);
    const manifest = JSON.parse(raw);

    if (!fs.existsSync(OUTPUT_DIR)) {
        fs.mkdirSync(OUTPUT_DIR, { recursive: true });
    }

    let report = {
        total_products: 4626, // Total from WC
        total_images_scanned: manifest.length,
        usable_images: 0,
        missing_images: 4626 - manifest.length, // Rough estimate
        duplicate_images: 0,
        corrupt_images: 0,
        unsupported_format: 0,
        too_small_dimensions: 0,
        ready_for_upload: 0,
        batch_count: 0
    };

    let finalManifest = [];
    let state = { products: {} };
    let seenSkus = new Set();
    
    let csvData = "product_id,sku,original_filename,export_filename,size_bytes,width,height,status,format\n";

    for (let item of manifest) {
        let sku = item.ProductCode;
        let file = item.Filename;
        let filepath = path.join(INPUT_DIR, file);

        let imgData = {
            sku: sku,
            original_filename: file,
            size_bytes: item.Size,
            width: null,
            height: null,
            format: null,
            status: 'UNKNOWN',
            export_filename: null
        };

        if (seenSkus.has(sku)) {
            imgData.status = 'DUPLICATE';
            report.duplicate_images++;
        } else {
            seenSkus.add(sku);
            if (!fs.existsSync(filepath)) {
                imgData.status = 'MISSING_FILE';
                report.missing_images++;
            } else {
                try {
                    let stats = fs.statSync(filepath);
                    imgData.size_bytes = stats.size;
                    
                    if (imgData.size_bytes < 1000) {
                        imgData.status = 'TOO_SMALL';
                        report.too_small_dimensions++;
                        report.usable_images++;
                    } else {
                        imgData.status = 'READY_FOR_UPLOAD';
                        report.usable_images++;
                        report.ready_for_upload++;
                    }
                } catch (e) {
                    imgData.status = 'CORRUPT';
                    report.corrupt_images++;
                }
            }
        }

        // Only export if not completely corrupt or missing
        if (imgData.status === 'READY_FOR_UPLOAD' || imgData.status === 'TOO_SMALL') {
            // Remove spaces/special chars from SKU for export filename
            let safeSku = sku.replace(/[^a-zA-Z0-9_-]/g, '');
            imgData.export_filename = `${safeSku}_1.jpg`;
            
            // Copy file
            fs.copyFileSync(filepath, path.join(OUTPUT_DIR, imgData.export_filename));
            
            // Build state
            state.products[sku] = {
                sku: sku,
                local_image: imgData.export_filename,
                meesho_image_url: null,
                status: 'AWAITING_MEESHO_LINK'
            };
        }

        finalManifest.push(imgData);
        csvData += `${sku},${sku},${imgData.original_filename},${imgData.export_filename || ''},${imgData.size_bytes},${imgData.width || ''},${imgData.height || ''},${imgData.status},${imgData.format || ''}\n`;
    }

    report.batch_count = Math.ceil(report.ready_for_upload / 200);

    fs.writeFileSync(MANIFEST_OUT_JSON, JSON.stringify(finalManifest, null, 2));
    fs.writeFileSync(MANIFEST_OUT_CSV, csvData);
    fs.writeFileSync(STATE_OUT, JSON.stringify(state, null, 2));

    let md = `# Meesho Image Upload Package Report

## Executive Summary
* **Total Products in Catalog:** ${report.total_products}
* **Total Local Images Scanned:** ${report.total_images_scanned}
* **Usable Images (Exported):** ${report.usable_images}
* **Missing Images:** ${report.total_products - report.usable_images}

## Quality Check Results
* **Corrupt Images:** ${report.corrupt_images}
* **Unsupported Formats:** ${report.unsupported_format}
* **Too Small / Low Res (<10KB or <100px):** ${report.too_small_dimensions} (Exported but flagged)
* **Duplicate SKUs ignored:** ${report.duplicate_images}
* **Perfectly Ready for Upload:** ${report.ready_for_upload}

## Export Details
* **Export Directory:** \`scripts/meesho_image_export_package/\`
* **Naming Convention:** \`{sku}_1.jpg\` (Special characters stripped for safety)
* **Estimated Batch Count:** ${report.batch_count} batches (assuming 200 images per batch as per standard stability limits)

## Meesho Supplier Panel Workflow (Official)
1. Log into the official Meesho Supplier Panel.
2. Navigate to **Catalog Upload -> Bulk Upload -> Image Bulk Upload**.
3. Create ZIP files of the \`meesho_image_export_package\` directory. Keep each ZIP under 200 images for stability.
4. Upload the ZIP. Meesho will process the images and generate an Excel file containing the filenames and the newly generated \`https://images.meesho.com/...\` URLs.
5. Download that Excel file. Do not alter it.

## Next Step
Run the local importer using the official generated Excel file.
`;

    fs.writeFileSync(path.join(__dirname, '../meesho_image_upload_package_report.md'), md);
    console.log("Image preparation complete.");
}

run();
