const express = require('express');
const fs = require('fs');
const path = require('path');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json({ limit: '10mb' }));
app.use(express.static(path.join(__dirname, 'public')));

const STATE_FILE = path.join(__dirname, 'meesho-image-link-state.json');
const BATCH_STATE_FILE = path.join(__dirname, 'meesho-image-batch-state.json');

function getState() {
    if (fs.existsSync(STATE_FILE)) return JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    return { products: {}, audit_log: [] };
}

function saveState(state) {
    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
}

function getBatchState() {
    if (fs.existsSync(BATCH_STATE_FILE)) return JSON.parse(fs.readFileSync(BATCH_STATE_FILE, 'utf8'));
    return { batches: [] };
}

function saveBatchState(state) {
    fs.writeFileSync(BATCH_STATE_FILE, JSON.stringify(state, null, 2));
}

function parseSkuAndSlot(filename) {
    let base = filename.replace(/\.[a-z0-9]+$/i, '');
    base = base.replace(/-Copy$/i, '');
    
    let slot = 1;
    let match = base.match(/_(\d+)$/);
    if (match) {
        slot = parseInt(match[1], 10);
        base = base.replace(/_\d+$/, '');
    }
    if (base) return { sku: base.toUpperCase(), slot };
    return null;
}

app.get('/api/batch-status', (req, res) => {
    let state = getBatchState();
    
    // Dynamically verify physical ZIP presence
    const ZIP_DIR = path.join(__dirname, 'meesho_image_export_package', 'zips');
    state.batches.forEach(b => {
        if (b.status === 'READY') {
            let zipPath = path.join(ZIP_DIR, b.zip_filename);
            if (!fs.existsSync(zipPath)) {
                b.status = 'MISSING_ZIP';
            }
        }
    });
    
    res.json(state);
});

app.post('/api/capture-links-batch', (req, res) => {
    let { rawText, dryRun, batchId } = req.body;
    let state = getState();
    let batchState = getBatchState();
    
    let batch = batchState.batches.find(b => b.id === batchId);
    if (!batch) return res.status(400).json({ errors: [`Batch ${batchId} not found.`] });
    if (!state.audit_log) state.audit_log = [];

    let stats = {
        total_rows_parsed: 0,
        valid_meesho_links: 0,
        matched_skus: 0,
        foreign_skus: 0,
        unknown_skus: 0,
        invalid_urls: 0,
        duplicates_in_paste: 0,
        expected_count: batch.image_count,
        missing_files: [],
        extra_urls: [],
        errors: [],
        dry_run: dryRun
    };

    let rawChunks = rawText.replace(/(https?:\/\/)/gi, '|$1').split('|');
    let processedMappings = [];
    let seenUrls = new Set();
    let seenFilenames = new Set();

    for (let chunk of rawChunks) {
        chunk = chunk.trim();
        if (!chunk.startsWith('http')) continue;
        stats.total_rows_parsed++;

        let urlMatch = chunk.match(/^(https?:\/\/[^\s\t]+)/i);
        if (!urlMatch) continue;
        
        let url = urlMatch[1];
        let extMatch = url.match(/(.*?\.jpg|.*?\.jpeg|.*?\.png)/i);
        if (extMatch) url = extMatch[1];

        if (!url.toLowerCase().startsWith('https://upload.meeshosupplyassets.com/')) {
            stats.invalid_urls++;
            stats.errors.push(`Invalid URL domain: ${url}`);
            continue;
        }

        if (seenUrls.has(url)) {
            stats.duplicates_in_paste++;
            stats.errors.push(`Duplicate URL in paste: ${url}`);
            continue;
        }
        seenUrls.add(url);

        stats.valid_meesho_links++;

        let filename = url.split('/').pop();
        let parsed = parseSkuAndSlot(filename);
        if (!parsed) {
            stats.unknown_skus++;
            stats.errors.push(`Could not extract SKU from URL filename: ${filename}`);
            continue;
        }

        processedMappings.push({ sku: parsed.sku, slot: parsed.slot, filename, url });
    }

    for (let map of processedMappings) {
        let { sku, slot, filename, url } = map;
        
        let targetSku = null;
        if (state.products[sku]) targetSku = sku;
        else {
            for (let k in state.products) {
                if (k.toUpperCase() === sku.toUpperCase()) {
                    targetSku = k;
                    break;
                }
            }
        }

        if (!targetSku) {
            stats.unknown_skus++;
            stats.errors.push(`Unknown SKU: ${sku} (from ${filename})`);
            continue;
        }

        if (!batch.filenames.includes(filename)) {
            stats.foreign_skus++;
            stats.errors.push(`FOREIGN SKU: ${filename} belongs to a different batch. You are capturing ${batchId}.`);
            continue;
        }

        if (seenFilenames.has(filename)) {
            stats.duplicates_in_paste++;
            stats.errors.push(`Duplicate filename mapping in paste: ${filename}`);
            continue; // already counted in valid links but shouldn't save twice
        }
        seenFilenames.add(filename);

        stats.matched_skus++;
    }

    // Verify all expected filenames were found
    for (let f of batch.filenames) {
        if (!seenFilenames.has(f)) {
            stats.missing_files.push(f);
            stats.errors.push(`Missing expected file: ${f}`);
        }
    }

    let isClean = (
        stats.valid_meesho_links === stats.expected_count &&
        stats.matched_skus === stats.expected_count &&
        stats.foreign_skus === 0 &&
        stats.unknown_skus === 0 &&
        stats.invalid_urls === 0 &&
        stats.duplicates_in_paste === 0 &&
        stats.missing_files.length === 0
    );

    if (!isClean) {
        stats.errors.unshift(`VALIDATION FAILED: Expected ${stats.expected_count} links, but verified ${stats.valid_meesho_links} clean links.`);
    }

    if (!dryRun && isClean) {
        // Execute saves
        for (let map of processedMappings) {
            let { sku, slot, filename, url } = map;
            
            let targetSku = null;
            for (let k in state.products) {
                if (k.toUpperCase() === sku.toUpperCase()) { targetSku = k; break; }
            }
            if(!targetSku && state.products[sku]) targetSku = sku;
            
            let prod = state.products[targetSku];
            if (!prod.meesho_image_urls) prod.meesho_image_urls = [];
            if (prod.meesho_image_urls.includes(url) || prod.meesho_image_url === url) continue;
            
            if (slot === 1) {
                prod.meesho_image_urls.unshift(url);
                prod.meesho_image_url = url;
            } else {
                prod.meesho_image_urls.push(url);
            }
            prod.status = 'VERIFIED_MEESHO_URL';
            prod.updated_at = new Date().toISOString();
            
            state.audit_log.push({
                timestamp: new Date().toISOString(),
                action: 'IMPORT_BATCH_IMAGE_LINK',
                batch_id: batchId,
                sku: targetSku,
                slot: slot,
                filename: filename,
                url: url
            });
        }

        saveState(state);
        batch.status = 'VERIFIED';
        batch.captured_sku_count = stats.matched_skus;
        batch.updated_at = new Date().toISOString();
        saveBatchState(batchState);
    }

    res.json(stats);
});

const PORT = 3002;
app.listen(PORT, () => {
    console.log(`Meesho Image Link Capture running at http://localhost:${PORT}/meesho-image-batch-dashboard.html`);
});
