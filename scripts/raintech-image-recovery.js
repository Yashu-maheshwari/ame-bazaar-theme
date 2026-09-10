/**
 * Raintech → WooCommerce Image Recovery Script
 * 
 * Phase 1: Reads extracted image files + manifest from PowerShell extractor.
 * Phase 2: Uploads to WordPress Media Library via WP REST API (Application Password).
 * Phase 3: Sets as product featured image via WC REST API.
 * 
 * IDEMPOTENT: Skips products that already have images.
 * SAFE: Never modifies product name/price/stock/SKU/weight/categories.
 */

require('dotenv').config({ path: require('path').join(__dirname, '.env') });
const https = require('https');
const fs = require('fs');
const path = require('path');

const WC_URL = process.env.WC_URL || 'https://amebazaar.in';
const WC_KEY = process.env.WC_CONSUMER_KEY;
const WC_SECRET = process.env.WC_CONSUMER_SECRET;
const WP_USER = process.env.WP_USERNAME;
const WP_PASS = process.env.WP_APP_PASSWORD;

if (!WC_KEY || !WC_SECRET) {
    console.error('ERROR: WC_CONSUMER_KEY / WC_CONSUMER_SECRET not set in .env');
    process.exit(1);
}
if (!WP_USER || !WP_PASS) {
    console.error('ERROR: WP_USERNAME / WP_APP_PASSWORD not set in .env');
    console.error('Create an Application Password at: https://amebazaar.in/wp-admin/profile.php');
    process.exit(1);
}

const BATCH_LIMIT = parseInt(process.argv[2] || '10', 10);
const MANIFEST_PATH = path.join(__dirname, 'extracted_manifest.json');
const IMAGES_DIR = path.join(__dirname, 'extracted_images');
const STATE_FILE = path.join(__dirname, 'recovery_state.json');

// ---- Helpers ----

function wcRequest(method, apiPath, bodyObj) {
    return new Promise((resolve, reject) => {
        const url = new URL(apiPath, WC_URL);
        const body = bodyObj ? JSON.stringify(bodyObj) : null;
        
        const options = {
            hostname: url.hostname,
            port: 443,
            path: url.pathname + url.search,
            method: method,
            auth: `${WC_KEY}:${WC_SECRET}`,
            headers: {
                'User-Agent': 'RaintechRecovery/1.0',
                'Content-Type': 'application/json'
            }
        };

        if (body) {
            options.headers['Content-Length'] = Buffer.byteLength(body);
        }

        const req = https.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                try {
                    resolve({ status: res.statusCode, data: JSON.parse(data) });
                } catch(e) {
                    resolve({ status: res.statusCode, data: data.substring(0, 500) });
                }
            });
        });
        req.on('error', reject);
        if (body) req.write(body);
        req.end();
    });
}

function wpMediaUpload(filePath, filename) {
    return new Promise((resolve, reject) => {
        const fileData = fs.readFileSync(filePath);
        const ext = path.extname(filename).toLowerCase();
        const mimeMap = { '.jpg': 'image/jpeg', '.jpeg': 'image/jpeg', '.png': 'image/png', '.gif': 'image/gif', '.bmp': 'image/bmp' };
        const mime = mimeMap[ext] || 'image/jpeg';

        // Use WordPress Application Password for /wp/v2/media
        const wpAuth = Buffer.from(`${WP_USER}:${WP_PASS}`).toString('base64');

        const boundary = '----RaintechBoundary' + Date.now();
        const header = `--${boundary}\r\nContent-Disposition: form-data; name="file"; filename="${filename}"\r\nContent-Type: ${mime}\r\n\r\n`;
        const footer = `\r\n--${boundary}--\r\n`;

        const bodyParts = [Buffer.from(header, 'utf8'), fileData, Buffer.from(footer, 'utf8')];
        const body = Buffer.concat(bodyParts);

        const options = {
            hostname: new URL(WC_URL).hostname,
            port: 443,
            path: '/wp-json/wp/v2/media',
            method: 'POST',
            headers: {
                'User-Agent': 'RaintechRecovery/1.0',
                'Authorization': `Basic ${wpAuth}`,
                'Content-Type': `multipart/form-data; boundary=${boundary}`,
                'Content-Length': body.length,
                'Content-Disposition': `attachment; filename="${filename}"`
            }
        };

        const req = https.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                try { resolve({ status: res.statusCode, data: JSON.parse(data) }); }
                catch(e) { resolve({ status: res.statusCode, data: data.substring(0, 500) }); }
            });
        });
        req.on('error', reject);
        req.write(body);
        req.end();
    });
}

function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

function loadState() {
    if (fs.existsSync(STATE_FILE)) {
        return JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    }
    return { recovered: {}, log: [] };
}

function saveState(state) {
    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
}

// ---- Main ----

async function run() {
    console.log(`\n=== RAINTECH → WOOCOMMERCE IMAGE RECOVERY ===`);
    console.log(`Batch limit: ${BATCH_LIMIT}`);

    // Load manifest
    if (!fs.existsSync(MANIFEST_PATH)) {
        console.error('ERROR: extracted_manifest.json not found. Run extract-raintech-blobs.ps1 first.');
        process.exit(1);
    }
    let manifestRaw = fs.readFileSync(MANIFEST_PATH, 'utf8').replace(/^\uFEFF/, '');
    const manifest = JSON.parse(manifestRaw);
    console.log(`Manifest entries: ${manifest.length}`);

    // Load recovery state (idempotency)
    const state = loadState();
    console.log(`Previously recovered: ${Object.keys(state.recovered).length}`);

    // Fetch WC products that are missing images
    console.log('Fetching WooCommerce products...');
    let allProducts = [];
    let page = 1;
    while (true) {
        const res = await wcRequest('GET', `/wp-json/wc/v3/products?per_page=100&status=publish&page=${page}&_fields=id,name,sku,images`);
        if (res.status !== 200 || !Array.isArray(res.data) || res.data.length === 0) break;
        allProducts = allProducts.concat(res.data);
        page++;
    }
    console.log(`Total WC products fetched: ${allProducts.length}`);

    // Build SKU → WC product map (only missing images)
    const missingImageMap = {};
    for (const p of allProducts) {
        if ((!p.images || p.images.length === 0) && p.sku) {
            missingImageMap[p.sku.trim().toLowerCase()] = p;
        }
    }
    console.log(`WC products missing images: ${Object.keys(missingImageMap).length}`);

    // Build manifest lookup by ProductCode
    const manifestMap = {};
    for (const m of manifest) {
        manifestMap[m.ProductCode.toLowerCase()] = m;
    }

    // Process in internal batches of 100
    let processed = 0;
    let succeeded = 0;
    let skippedAlreadyRecovered = 0;
    let uploadFailed = 0;
    let noMatch = 0;
    let invalidBlob = 0;
    let consecutiveFailures = 0;

    const skus = Object.keys(missingImageMap);
    const BATCH_SIZE = 100;
    const totalToProcess = Math.min(skus.length, BATCH_LIMIT);

    console.log(`\nTargeting ${totalToProcess} products for recovery...`);

    for (let i = 0; i < totalToProcess; i++) {
        const sku = skus[i];
        const wcProduct = missingImageMap[sku];
        
        // Already recovered in a previous run?
        if (state.recovered[sku]) {
            skippedAlreadyRecovered++;
            continue;
        }

        // Find in manifest
        const entry = manifestMap[sku];
        if (!entry) {
            noMatch++;
            continue;
        }

        const imagePath = path.join(IMAGES_DIR, entry.Filename);
        if (!fs.existsSync(imagePath)) {
            noMatch++;
            continue;
        }

        // Validate file size
        const fileStat = fs.statSync(imagePath);
        if (fileStat.size < 100) {
            console.log(`[SKIP] ${sku} - image too small (${fileStat.size} bytes)`);
            invalidBlob++;
            continue;
        }

        processed++;
        console.log(`\n[${processed}/${totalToProcess}] Processing: WC#${wcProduct.id} SKU=${wcProduct.sku} "${wcProduct.name}"`);

        // Step 1: Upload image to WP Media Library via Application Password
        console.log(`  Uploading ${entry.Filename} (${fileStat.size} bytes)...`);
        let uploadRes;
        try {
            uploadRes = await wpMediaUpload(imagePath, entry.Filename);
        } catch(e) {
            console.log(`  [FAIL] Upload error: ${e.message}`);
            uploadFailed++;
            consecutiveFailures++;
            state.log.push({ sku, wcId: wcProduct.id, status: 'upload_failed', error: e.message });
            saveState(state);
            await sleep(1000);
            if (consecutiveFailures >= 10) { console.error('SYSTEMIC ERROR: 10 consecutive failures. Stopping.'); break; }
            continue;
        }

        if (uploadRes.status !== 201 && uploadRes.status !== 200) {
            const errMsg = typeof uploadRes.data === 'object' ? (uploadRes.data.message || JSON.stringify(uploadRes.data)) : String(uploadRes.data).substring(0, 200);
            console.log(`  [FAIL] Upload HTTP ${uploadRes.status}: ${errMsg}`);
            uploadFailed++;
            consecutiveFailures++;
            state.log.push({ sku, wcId: wcProduct.id, status: 'upload_failed', httpStatus: uploadRes.status, error: errMsg });
            saveState(state);
            await sleep(1000);
            if (consecutiveFailures >= 10) { console.error('SYSTEMIC ERROR: 10 consecutive failures. Stopping.'); break; }
            continue;
        }

        const mediaId = uploadRes.data.id;
        const mediaUrl = uploadRes.data.source_url;
        console.log(`  Media uploaded: ID=${mediaId}`);

        // Step 2: Set as product featured image via WC API (ONLY images field)
        let updateRes;
        try {
            updateRes = await wcRequest('PUT', `/wp-json/wc/v3/products/${wcProduct.id}`, {
                images: [{ id: mediaId }]
            });
        } catch(e) {
            console.log(`  [FAIL] Product update error: ${e.message}`);
            uploadFailed++;
            consecutiveFailures++;
            state.log.push({ sku, wcId: wcProduct.id, mediaId, status: 'product_update_failed', error: e.message });
            saveState(state);
            await sleep(1000);
            if (consecutiveFailures >= 10) { console.error('SYSTEMIC ERROR: 10 consecutive failures. Stopping.'); break; }
            continue;
        }

        if (updateRes.status !== 200) {
            const errMsg = typeof updateRes.data === 'object' ? (updateRes.data.message || JSON.stringify(updateRes.data)) : String(updateRes.data).substring(0, 200);
            console.log(`  [FAIL] Product update HTTP ${updateRes.status}: ${errMsg}`);
            uploadFailed++;
            consecutiveFailures++;
            state.log.push({ sku, wcId: wcProduct.id, mediaId, status: 'product_update_failed', httpStatus: updateRes.status, error: errMsg });
            saveState(state);
            await sleep(1000);
            if (consecutiveFailures >= 10) { console.error('SYSTEMIC ERROR: 10 consecutive failures. Stopping.'); break; }
            continue;
        }

        // Verify
        const updatedImages = updateRes.data.images || [];
        const hasImage = updatedImages.some(img => img.id === mediaId);
        const skuUnchanged = updateRes.data.sku === wcProduct.sku;

        if (hasImage && skuUnchanged) {
            console.log(`  [OK] Image set. Media ID=${mediaId}`);
            console.log(`  [OK] SKU verified unchanged: ${updateRes.data.sku}`);
            succeeded++;
            consecutiveFailures = 0; // reset on success
            state.recovered[sku] = { wcId: wcProduct.id, mediaId, mediaUrl, timestamp: new Date().toISOString() };
            state.log.push({ sku, wcId: wcProduct.id, mediaId, mediaUrl, status: 'success' });
        } else {
            console.log(`  [WARN] Verification issue. hasImage=${hasImage}, skuUnchanged=${skuUnchanged}`);
            uploadFailed++;
            consecutiveFailures++;
            state.log.push({ sku, wcId: wcProduct.id, mediaId, status: 'verification_failed', hasImage, skuUnchanged });
        }

        saveState(state);
        
        // Batch summary every 100
        if (processed % BATCH_SIZE === 0) {
            console.log(`\n--- BATCH OF ${BATCH_SIZE} COMPLETED ---`);
            console.log(`Progress: ${processed}/${totalToProcess}`);
            console.log(`Recent Successes: ${succeeded}, Recent Failures: ${uploadFailed}`);
            console.log(`Waiting 5 seconds before next batch...`);
            await sleep(5000);
        } else {
            await sleep(500); // Normal rate limiting
        }
    }

    console.log(`\n=== RECOVERY COMPLETE ===`);
    console.log(`Processed:                ${processed}`);
    console.log(`Successfully recovered:   ${succeeded}`);
    console.log(`Skipped (prev recovered): ${skippedAlreadyRecovered}`);
    console.log(`Upload/update failed:     ${uploadFailed}`);
    console.log(`Invalid BLOBs:            ${invalidBlob}`);
    console.log(`No Raintech match:        ${noMatch}`);
    console.log(`Total now recovered:      ${Object.keys(state.recovered).length}`);
}

run().catch(e => { console.error('FATAL:', e); process.exit(1); });
