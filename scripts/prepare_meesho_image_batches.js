const fs = require('fs');
const path = require('path');
const AdmZip = require('adm-zip');

const EXPORT_DIR = path.join(__dirname, 'meesho_image_export_package');
const ZIP_DIR = path.join(__dirname, 'meesho_image_export_package', 'zips');
const MANIFEST_FILE = path.join(__dirname, 'meesho_image_manifest.json');
const LINK_STATE_FILE = path.join(__dirname, 'meesho-image-link-state.json');
const BATCH_STATE_FILE = path.join(__dirname, 'meesho-image-batch-state.json');

const BATCH_SIZE = 200;

function run() {
    console.log("Loading manifest and state...");
    const manifest = JSON.parse(fs.readFileSync(MANIFEST_FILE, 'utf8'));
    
    let linkState = { products: {} };
    if (fs.existsSync(LINK_STATE_FILE)) {
        linkState = JSON.parse(fs.readFileSync(LINK_STATE_FILE, 'utf8'));
    }

    if (!fs.existsSync(ZIP_DIR)) {
        fs.mkdirSync(ZIP_DIR, { recursive: true });
    }

    let batchState = { batches: [] };
    if (fs.existsSync(BATCH_STATE_FILE)) {
        batchState = JSON.parse(fs.readFileSync(BATCH_STATE_FILE, 'utf8'));
        // If it exists, we don't necessarily want to wipe it out if they've made progress.
        // But for this setup, we assume it's a fresh grouping. Let's rebuild it cleanly.
        // We'll retain status if we can match it.
    }

    let pendingImages = [];
    let alreadyVerified = 0;

    for (let item of manifest) {
        if (item.status !== 'READY_FOR_UPLOAD') continue;
        
        let pState = linkState.products[item.sku];
        if (pState && pState.status === 'VERIFIED_MEESHO_URL') {
            alreadyVerified++;
            continue;
        }

        pendingImages.push(item);
    }

    console.log(`Total ready images: ${manifest.length}`);
    console.log(`Already verified: ${alreadyVerified}`);
    console.log(`Pending grouping: ${pendingImages.length}`);

    let chunks = [];
    for (let i = 0; i < pendingImages.length; i += BATCH_SIZE) {
        chunks.push(pendingImages.slice(i, i + BATCH_SIZE));
    }

    console.log(`Creating ${chunks.length} batches...`);

    let newBatches = [];

    chunks.forEach((chunk, index) => {
        let batchId = `Batch_${(index + 1).toString().padStart(2, '0')}`;
        let zipFilename = `${batchId}.zip`;
        let zipPath = path.join(ZIP_DIR, zipFilename);
        
        // Always generate the zip if it doesn't exist
        if (!fs.existsSync(zipPath)) {
            let zip = new AdmZip();
            chunk.forEach(img => {
                let imgPath = path.join(EXPORT_DIR, img.export_filename);
                if (fs.existsSync(imgPath)) {
                    zip.addLocalFile(imgPath);
                }
            });
            zip.writeZip(zipPath);
            console.log(`Created ${zipFilename} (${chunk.length} images)`);
        }

        let skus = chunk.map(img => img.sku);
        let filenames = chunk.map(img => img.export_filename);
        
        newBatches.push({
            id: batchId,
            zip_filename: zipFilename,
            status: 'READY',
            sku_count: chunk.length,
            image_count: chunk.length,
            skus: skus,
            filenames: filenames,
            captured_sku_count: 0,
            duplicate_count: 0,
            unknown_count: 0,
            invalid_url_count: 0,
            created_at: new Date().toISOString()
        });
    });

    batchState.batches = newBatches;
    fs.writeFileSync(BATCH_STATE_FILE, JSON.stringify(batchState, null, 2));

    console.log("Batch preparation complete.");
}

run();
