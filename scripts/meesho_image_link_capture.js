const express = require('express');
const fs = require('fs');
const path = require('path');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json({ limit: '10mb' }));
app.use(express.static(path.join(__dirname, 'public')));

const STATE_FILE = path.join(__dirname, 'meesho-image-link-state.json');

function getState() {
    if (fs.existsSync(STATE_FILE)) {
        return JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    }
    return { products: {}, audit_log: [] };
}

function saveState(state) {
    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
}

function parseSkuFromFilename(filename) {
    let base = filename.replace(/\.[a-z0-9]+$/i, '');
    base = base.replace(/-Copy$/i, '');
    base = base.replace(/_\d+$/, '');
    if (base) return base.toUpperCase();
    return null;
}

app.post('/api/capture-links', (req, res) => {
    let { rawText, dryRun } = req.body;
    let state = getState();
    if (!state.audit_log) state.audit_log = [];

    let stats = {
        total_rows_parsed: 0,
        valid_meesho_links: 0,
        matched_skus: 0,
        unknown_skus: 0,
        invalid_urls: 0,
        duplicate_links: 0,
        already_imported: 0,
        newly_imported: 0,
        errors: [],
        dry_run: dryRun
    };

    // Very robust TSV / raw text parsing
    let lines = rawText.split(/\r?\n/);
    
    // We will extract anything that looks like a filename and anything that looks like a meesho URL on the same line
    const urlRegex = /https:\/\/upload\.meeshosupplyassets\.com\/[^\s\t]+/i;
    const fileRegex = /([A-Za-z0-9_-]+(?:-Copy)?\.[a-z]{3,4})/i;

    let processedMappings = [];

    for (let line of lines) {
        line = line.trim();
        if (!line) continue;

        let urlMatch = line.match(urlRegex);
        let fileMatch = line.match(fileRegex);

        if (urlMatch || fileMatch) {
            stats.total_rows_parsed++;
        }

        if (!urlMatch) {
            if (fileMatch) {
                stats.invalid_urls++;
                stats.errors.push(`Row has filename ${fileMatch[1]} but no valid upload.meeshosupplyassets.com URL.`);
            }
            continue;
        }

        let url = urlMatch[0];
        
        if (!fileMatch) {
            stats.invalid_urls++;
            stats.errors.push(`Row has URL ${url} but no recognizable filename.`);
            continue;
        }

        let filename = fileMatch[1];
        stats.valid_meesho_links++;

        let sku = parseSkuFromFilename(filename);
        if (!sku) {
            stats.unknown_skus++;
            stats.errors.push(`Could not extract SKU from filename: ${filename}`);
            continue;
        }

        processedMappings.push({ sku, filename, url });
    }

    for (let map of processedMappings) {
        let { sku, filename, url } = map;
        
        // Find if SKU exists in state. Since user might have lowercase/uppercase mismatches, let's be careful.
        let targetSku = null;
        if (state.products[sku]) targetSku = sku;
        else {
            // Case insensitive search
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

        stats.matched_skus++;
        let prod = state.products[targetSku];

        if (!prod.meesho_image_urls) {
            prod.meesho_image_urls = [];
        }

        // Check for duplicates in the current product's URLs
        if (prod.meesho_image_urls.includes(url) || prod.meesho_image_url === url) {
            stats.duplicate_links++;
            continue;
        }

        // Check if we already have this filename mapped (overwrite protection)
        // For simplicity, we just append to the array. If they want to reset, we'd need a clear function.
        if (prod.status === 'VERIFIED_MEESHO_URL' && prod.meesho_image_urls.length > 0) {
            stats.already_imported++;
        }

        if (!dryRun) {
            prod.meesho_image_urls.push(url);
            // set the primary one for backward compatibility if it's null
            if (!prod.meesho_image_url) {
                prod.meesho_image_url = url;
            }
            prod.status = 'VERIFIED_MEESHO_URL';
            prod.updated_at = new Date().toISOString();
            
            state.audit_log.push({
                timestamp: new Date().toISOString(),
                action: 'IMPORT_IMAGE_LINK',
                sku: targetSku,
                filename: filename,
                url: url
            });
        }
        stats.newly_imported++;
    }

    if (!dryRun) {
        saveState(state);
    }

    res.json(stats);
});

// Create HTML file on startup for ease of use if not exists
const HTML_FILE = path.join(__dirname, 'public', 'meesho_image_link_capture.html');
if (!fs.existsSync(path.join(__dirname, 'public'))) {
    fs.mkdirSync(path.join(__dirname, 'public'));
}

const htmlContent = `<!DOCTYPE html>
<html>
<head>
    <title>Meesho Image Link Capture</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f2f5; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        textarea { width: 100%; height: 200px; padding: 10px; font-family: monospace; box-sizing: border-box; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; font-weight: bold; }
        .btn-primary { background: #007bff; }
        .btn-warning { background: #ffc107; color: black; }
        .results { margin-top: 20px; padding: 15px; background: #e9ecef; border-radius: 4px; display: none; }
        .error-list { color: #dc3545; font-size: 0.9em; max-height: 200px; overflow-y: auto; }
    </style>
</head>
<body>
<div class="container">
    <h2>Meesho Image Link Capture Workflow</h2>
    <p>Because there is no official bulk "Export" button on the Meesho Supplier Panel Image Link Generator, we use this safe capture tool.</p>
    <p><strong>Instructions:</strong> Highlight the entire table on the Meesho Supplier Panel (including Image, Title, Link, Actions columns), copy it (Ctrl+C), and paste it below (Ctrl+V).</p>
    
    <textarea id="paste-area" placeholder="Paste Meesho Supplier Panel table data here..."></textarea>
    
    <div style="margin-top: 15px; display: flex; gap: 10px;">
        <button class="btn btn-warning" onclick="processLinks(true)">Dry Run (Test)</button>
        <button class="btn btn-primary" onclick="processLinks(false)">Import & Save</button>
    </div>

    <div id="results" class="results">
        <h3>Import Results <span id="run-type"></span></h3>
        <p><strong>Total rows parsed:</strong> <span id="r-total">0</span></p>
        <p><strong>Valid Meesho Links (upload.meeshosupplyassets.com):</strong> <span id="r-valid">0</span></p>
        <p><strong>Matched SKUs:</strong> <span id="r-matched">0</span></p>
        <p><strong>Newly Imported:</strong> <span id="r-new">0</span></p>
        <p><strong>Duplicate Links (ignored):</strong> <span id="r-dup">0</span></p>
        <p><strong>Unknown SKUs:</strong> <span id="r-unk">0</span></p>
        <p><strong>Invalid URLs:</strong> <span id="r-inv">0</span></p>
        
        <div id="errors-container" style="display:none; margin-top: 10px;">
            <h4>Warnings / Errors:</h4>
            <div id="error-list" class="error-list"></div>
        </div>
    </div>
</div>

<script>
async function processLinks(dryRun) {
    let rawText = document.getElementById('paste-area').value;
    if (!rawText.trim()) {
        alert("Please paste some data first.");
        return;
    }

    let res = await fetch('/api/capture-links', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ rawText, dryRun })
    });
    
    let stats = await res.json();
    
    document.getElementById('results').style.display = 'block';
    document.getElementById('run-type').innerText = dryRun ? '(DRY RUN)' : '(SAVED)';
    document.getElementById('r-total').innerText = stats.total_rows_parsed;
    document.getElementById('r-valid').innerText = stats.valid_meesho_links;
    document.getElementById('r-matched').innerText = stats.matched_skus;
    document.getElementById('r-new').innerText = stats.newly_imported;
    document.getElementById('r-dup').innerText = stats.duplicate_links;
    document.getElementById('r-unk').innerText = stats.unknown_skus;
    document.getElementById('r-inv').innerText = stats.invalid_urls;

    let errCont = document.getElementById('errors-container');
    let errList = document.getElementById('error-list');
    if (stats.errors && stats.errors.length > 0) {
        errCont.style.display = 'block';
        errList.innerHTML = stats.errors.map(e => \`<div>\${e}</div>\`).join('');
    } else {
        errCont.style.display = 'none';
        errList.innerHTML = '';
    }
    
    if (!dryRun && stats.newly_imported > 0) {
        alert("Successfully imported " + stats.newly_imported + " links!");
        document.getElementById('paste-area').value = ''; // clear on success
    }
}
</script>
</body>
</html>`;

fs.writeFileSync(HTML_FILE, htmlContent);

const PORT = 3002;
app.listen(PORT, () => {
    console.log(`Meesho Image Link Capture running at http://localhost:${PORT}/meesho_image_link_capture.html`);
});
