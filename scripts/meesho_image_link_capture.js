const express = require('express');
const fs = require('fs');
const path = require('path');
const bodyParser = require('body-parser');
const cors = require('cors');
const { exec } = require('child_process');

const app = express();
app.use(cors({
    origin: '*',
    methods: ['GET', 'POST', 'OPTIONS'],
    allowedHeaders: ['Content-Type']
}));
app.use(bodyParser.json({ limit: '25mb' }));

// Disable browser caching for all responses
app.use((req, res, next) => {
    res.set('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate');
    res.set('Pragma', 'no-cache');
    res.set('Expires', '0');
    res.set('Surrogate-Control', 'no-store');
    next();
});

app.use(express.static(path.join(__dirname, 'public')));

const STATE_FILE = path.join(__dirname, 'meesho-image-link-state.json');
const BATCH_STATE_FILE = path.join(__dirname, 'meesho-image-batch-state.json');
const ZIP_DIR = path.join(__dirname, 'meesho_image_export_package', 'zips');

function getState() {
    if (fs.existsSync(STATE_FILE)) return JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    return { products: {}, audit_log: [] };
}

function getBatchState() {
    if (fs.existsSync(BATCH_STATE_FILE)) return JSON.parse(fs.readFileSync(BATCH_STATE_FILE, 'utf8'));
    return { batches: [] };
}

function atomicWriteJson(filePath, data) {
    const tmpPath = `${filePath}.tmp.${Date.now()}`;
    fs.writeFileSync(tmpPath, JSON.stringify(data, null, 2), 'utf8');
    try {
        if (fs.existsSync(filePath)) {
            fs.copyFileSync(tmpPath, filePath);
            fs.unlinkSync(tmpPath);
        } else {
            fs.renameSync(tmpPath, filePath);
        }
    } catch (e) {
        fs.writeFileSync(filePath, JSON.stringify(data, null, 2), 'utf8');
        if (fs.existsSync(tmpPath)) {
            try { fs.unlinkSync(tmpPath); } catch (_) {}
        }
    }
}

function saveState(state) {
    atomicWriteJson(STATE_FILE, state);
}

function saveBatchState(state) {
    atomicWriteJson(BATCH_STATE_FILE, state);
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

// Reveal ZIP or folder in Windows File Explorer
app.post('/api/open-zip-folder', (req, res) => {
    let { filename } = req.body || {};
    let target = ZIP_DIR;
    if (filename) {
        let filePath = path.join(ZIP_DIR, filename);
        if (fs.existsSync(filePath)) {
            exec(`explorer.exe /select,"${filePath}"`);
            return res.json({ success: true, opened: filePath });
        }
    }
    exec(`explorer.exe "${target}"`);
    res.json({ success: true, opened: target });
});

app.get('/api/batch-status', (req, res) => {
    let state = getBatchState();
    let nextBatch = null;
    let completedImages = 0;
    let totalImages = 0;
    let verifiedBatches = 0;
    let readyBatches = 0;
    let failedBatches = 0;

    state.batches.forEach(b => {
        totalImages += b.image_count;
        let zipPath = path.join(ZIP_DIR, b.zip_filename);
        let exists = fs.existsSync(zipPath);

        if (b.status === 'VERIFIED') {
            completedImages += b.image_count;
            verifiedBatches++;
        } else if (b.status === 'READY') {
            readyBatches++;
            if (!exists) {
                b.status = 'MISSING_ZIP';
            } else if (!nextBatch) {
                nextBatch = {
                    id: b.id,
                    zip_filename: b.zip_filename,
                    image_count: b.image_count,
                    physical_path: zipPath,
                    status: b.status,
                    skus: b.skus,
                    filenames: b.filenames
                };
            }
        } else if (b.status === 'FAILED') {
            failedBatches++;
        }
    });

    res.json({
        batches: state.batches,
        next_batch: nextBatch,
        summary: {
            total_batches: state.batches.length,
            verified_batches: verifiedBatches,
            ready_batches: readyBatches,
            failed_batches: failedBatches,
            total_images: totalImages,
            completed_images: completedImages,
            remaining_images: totalImages - completedImages,
            progress_percent: totalImages > 0 ? ((completedImages / totalImages) * 100).toFixed(1) : 0
        },
        zip_directory: ZIP_DIR
    });
});

app.post('/api/capture-links-batch', (req, res) => {
    let { rawText, urls, dryRun, batchId } = req.body;
    let state = getState();
    let batchState = getBatchState();
    
    // Auto-detect current batch if not explicitly passed
    if (!batchId) {
        let nextReady = batchState.batches.find(b => b.status === 'READY');
        if (nextReady) batchId = nextReady.id;
    }

    let batch = batchState.batches.find(b => b.id === batchId);
    if (!batch) return res.status(400).json({ is_clean: false, errors: [`Batch ${batchId} not found in manifest.`] });
    if (!state.audit_log) state.audit_log = [];

    let stats = {
        batch_id: batchId,
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
        duplicate_details: [],
        foreign_details: [],
        errors: [],
        dry_run: !!dryRun,
        is_clean: false,
        saved: false,
        next_batch: null
    };

    // Support both rawText string and urls array
    let rawChunks = [];
    if (Array.isArray(urls) && urls.length > 0) {
        rawChunks = urls;
    } else if (rawText && typeof rawText === 'string') {
        rawChunks = rawText.replace(/(https?:\/\/)/gi, '|$1').split('|');
    } else {
        stats.errors.push("No URL data provided. Please paste or send Meesho Image Links.");
        return res.json(stats);
    }

    let processedMappings = [];
    let seenUrls = new Set();
    let seenFilenames = new Set();

    for (let chunk of rawChunks) {
        chunk = (chunk || '').trim();
        if (!chunk.startsWith('http')) continue;
        stats.total_rows_parsed++;

        let urlMatch = chunk.match(/^(https?:\/\/[^\s\t]+)/i);
        if (!urlMatch) continue;
        
        let url = urlMatch[1];
        let extMatch = url.match(/(.*?\.jpg|.*?\.jpeg|.*?\.png)/i);
        if (extMatch) url = extMatch[1];

        if (!url.toLowerCase().startsWith('https://upload.meeshosupplyassets.com/cataloging/')) {
            stats.invalid_urls++;
            stats.errors.push(`Invalid URL domain (expected upload.meeshosupplyassets.com): ${url}`);
            continue;
        }

        if (seenUrls.has(url)) {
            stats.duplicates_in_paste++;
            stats.duplicate_details.push(url);
            stats.errors.push(`Duplicate URL: ${url}`);
            continue;
        }
        seenUrls.add(url);

        stats.valid_meesho_links++;

        let filename = url.split('/').pop();
        let parsed = parseSkuAndSlot(filename);
        if (!parsed) {
            stats.unknown_skus++;
            stats.errors.push(`Could not extract SKU from filename: ${filename}`);
            continue;
        }

        processedMappings.push({ sku: parsed.sku, slot: parsed.slot, filename, url });
    }

    // Validate SKU membership against catalog and batch
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
            stats.errors.push(`Unknown SKU in product catalog: ${sku} (from ${filename})`);
            continue;
        }

        if (!batch.filenames.includes(filename)) {
            stats.foreign_skus++;
            stats.foreign_details.push(filename);
            stats.errors.push(`FOREIGN FILE: ${filename} does not belong to ${batchId}.`);
            continue;
        }

        if (seenFilenames.has(filename)) {
            stats.duplicates_in_paste++;
            stats.duplicate_details.push(filename);
            stats.errors.push(`Duplicate filename: ${filename}`);
            continue;
        }
        seenFilenames.add(filename);

        stats.matched_skus++;
    }

    // Identify missing expected files
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

    stats.is_clean = isClean;

    if (!isClean) {
        stats.errors.unshift(`VALIDATION FAILED: Expected ${stats.expected_count} links, but verified ${stats.matched_skus} valid clean links.`);
    }

    // Execute atomic saves only if clean and not dry run
    if (!dryRun && isClean) {
        const nowIso = new Date().toISOString();

        for (let map of processedMappings) {
            let { sku, slot, filename, url } = map;
            
            let targetSku = null;
            for (let k in state.products) {
                if (k.toUpperCase() === sku.toUpperCase()) { targetSku = k; break; }
            }
            if (!targetSku && state.products[sku]) targetSku = sku;
            
            let prod = state.products[targetSku];
            if (!prod.meesho_image_urls) prod.meesho_image_urls = [];
            
            // Preserve existing verified URLs, never overwrite
            if (!prod.meesho_image_urls.includes(url)) {
                if (slot === 1) {
                    prod.meesho_image_urls.unshift(url);
                    prod.meesho_image_url = url;
                } else {
                    prod.meesho_image_urls.push(url);
                }
            }
            
            prod.status = 'VERIFIED_MEESHO_URL';
            prod.updated_at = nowIso;
            
            state.audit_log.push({
                timestamp: nowIso,
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
        batch.updated_at = nowIso;
        saveBatchState(batchState);

        stats.saved = true;

        // Auto-detect next READY batch
        let nextReady = batchState.batches.find(b => b.status === 'READY');
        if (nextReady) {
            stats.next_batch = {
                id: nextReady.id,
                zip_filename: nextReady.zip_filename,
                image_count: nextReady.image_count,
                physical_path: path.join(ZIP_DIR, nextReady.zip_filename)
            };
        }
    }

    res.json(stats);
});

const PORT = 3002;
app.listen(PORT, () => {
    console.log(`Meesho Image Link Capture running at http://localhost:${PORT}/meesho-image-batch-dashboard.html`);
});
