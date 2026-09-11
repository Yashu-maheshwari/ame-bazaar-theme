const fs = require('fs');
const path = require('path');

const STATE_FILE = path.join(__dirname, 'meesho-image-link-state.json');

// Example usage: node meesho_image_link_importer.js "path/to/meesho_output.csv"
function run() {
    const inputFile = process.argv[2];
    if (!inputFile || !fs.existsSync(inputFile)) {
        console.error("Usage: node meesho_image_link_importer.js <path_to_meesho_generated_csv_or_json>");
        process.exit(1);
    }

    console.log(`Importing official Meesho URLs from ${inputFile}...`);

    let state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    let raw = fs.readFileSync(inputFile, 'utf8');
    
    // Simple CSV/JSON parser assuming format: filename, meesho_url
    let mappings = [];
    if (inputFile.endsWith('.json')) {
        mappings = JSON.parse(raw);
    } else {
        // Basic CSV parsing
        let lines = raw.split('\n');
        for (let i = 1; i < lines.length; i++) {
            if (!lines[i].trim()) continue;
            let cols = lines[i].split(',');
            if (cols.length >= 2) {
                mappings.push({
                    filename: cols[0].trim().replace(/['"]/g, ''),
                    url: cols[1].trim().replace(/['"]/g, '')
                });
            }
        }
    }

    let successCount = 0;
    let notFoundCount = 0;
    let invalidUrlCount = 0;

    for (let map of mappings) {
        let filename = map.filename;
        let url = map.url;

        if (!url || (!url.includes('images.meesho.com') && !url.includes('meeshosupply.com'))) {
            console.log(`[REJECTED] Invalid or non-Meesho URL for ${filename}: ${url}`);
            invalidUrlCount++;
            continue;
        }

        // Find which SKU this filename belongs to
        let foundSku = null;
        for (let sku in state.products) {
            if (state.products[sku].local_image === filename || state.products[sku].sku === filename.replace('_1.jpg', '').replace('_1.png', '')) {
                foundSku = sku;
                break;
            }
        }

        if (foundSku) {
            state.products[foundSku].meesho_image_url = url;
            state.products[foundSku].status = 'VERIFIED_MEESHO_URL';
            state.products[foundSku].updated_at = new Date().toISOString();
            successCount++;
        } else {
            console.log(`[NOT FOUND] Filename ${filename} does not match any known SKU`);
            notFoundCount++;
        }
    }

    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));

    console.log(`\nImport Complete:`);
    console.log(`Successfully mapped: ${successCount} URLs`);
    console.log(`Rejected (Invalid URL): ${invalidUrlCount}`);
    console.log(`Rejected (Unknown SKU): ${notFoundCount}`);
}

run();
