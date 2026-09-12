const fs = require('fs');
const http = require('http');

async function sendDryRun(rawText, batchId) {
    return new Promise((resolve, reject) => {
        const payload = JSON.stringify({ rawText, dryRun: true, batchId });
        const options = {
            hostname: 'localhost',
            port: 3002,
            path: '/api/capture-links-batch',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Content-Length': payload.length
            }
        };

        const req = http.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => resolve(JSON.parse(data)));
        });

        req.on('error', e => reject(e));
        req.write(payload);
        req.end();
    });
}

async function run() {
    let batchState = JSON.parse(fs.readFileSync('scripts/meesho-image-batch-state.json', 'utf8'));
    let batch = batchState.batches.find(b => b.id === 'Batch_TEST_50');
    if (!batch) throw new Error("Batch_TEST_50 not found.");

    // Generate exactly 50 clean URLs for this batch
    let baseText = '';
    batch.filenames.forEach(f => {
        baseText += `https://upload.meeshosupplyassets.com/cataloging/123/${f} `;
    });

    console.log("=== REGRESSION TEST: 50 Expected + 50 Received ===");
    let res50 = await sendDryRun(baseText, 'Batch_TEST_50');
    let isClean50 = (
        res50.valid_meesho_links === 50 &&
        res50.matched_skus === 50 &&
        res50.foreign_skus === 0 &&
        res50.duplicates_in_paste === 0 &&
        res50.missing_files.length === 0
    );
    console.log("Status: " + (isClean50 ? "PASS" : "FAIL"), res50);

    console.log("\n=== REGRESSION TEST: 50 Expected + 51 Received (1 Duplicate) ===");
    // Add one duplicate filename
    let extraText = baseText + `https://upload.meeshosupplyassets.com/cataloging/123/${batch.filenames[0]}`;
    let res51 = await sendDryRun(extraText, 'Batch_TEST_50');
    
    let isClean51 = (
        res51.valid_meesho_links === 50 &&
        res51.matched_skus === 50 &&
        res51.foreign_skus === 0 &&
        res51.duplicates_in_paste === 0 &&
        res51.missing_files.length === 0
    );
    // We expect it to FAIL validation (isClean51 should be FALSE)
    console.log("Status: " + (!isClean51 ? "PASS (Successfully rejected)" : "FAIL (Incorrectly accepted)"));
    console.log("Errors captured:", res51.errors);
}

run().catch(console.error);
