const fs = require('fs');
const http = require('http');

async function request(options, data) {
    return new Promise((resolve, reject) => {
        const req = http.request(options, (res) => {
            let body = '';
            res.on('data', chunk => body += chunk);
            res.on('end', () => {
                try { resolve(JSON.parse(body)); }
                catch (e) { resolve(body); }
            });
        });
        req.on('error', reject);
        if (data) req.write(typeof data === 'string' ? data : JSON.stringify(data));
        req.end();
    });
}

async function runTests() {
    console.log("==================================================");
    console.log(" TESTING ENHANCED LOCAL WORKFLOW & VALIDATION");
    console.log("==================================================\n");

    let results = {};

    // 1. Test GET /api/batch-status
    console.log("[TEST 1] Current Batch Detection via /api/batch-status...");
    const statusRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/batch-status',
        method: 'GET'
    });

    const isDetectionPass = (
        statusRes.summary &&
        statusRes.summary.total_batches === 73 &&
        statusRes.next_batch &&
        statusRes.next_batch.id === 'Batch_01' &&
        statusRes.next_batch.image_count === 50 &&
        fs.existsSync(statusRes.next_batch.physical_path)
    );

    results['CURRENT_BATCH_DETECTION'] = isDetectionPass ? 'PASS' : 'FAIL';
    console.log(`  -> Current Next Batch: ${statusRes.next_batch ? statusRes.next_batch.id : 'NONE'}`);
    console.log(`  -> Expected Count: ${statusRes.next_batch ? statusRes.next_batch.image_count : 0}`);
    console.log(`  -> Physical ZIP: ${statusRes.next_batch ? statusRes.next_batch.physical_path : 'NONE'}`);
    console.log(`  Result: ${results['CURRENT_BATCH_DETECTION']}\n`);

    // Prepare synthetic data for Batch_01
    const batch01 = statusRes.next_batch;
    const valid50Urls = batch01.filenames.map(f => `https://upload.meeshosupplyassets.com/cataloging/1789234567890/${f}`).join('\n');

    // 2. Test URL & SKU Validation (Clean 50)
    console.log("[TEST 2] 50 Clean Expected URLs (Dry Run)...");
    const cleanRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        rawText: valid50Urls
    });

    const isUrlSkuPass = (
        cleanRes.is_clean === true &&
        cleanRes.valid_meesho_links === 50 &&
        cleanRes.matched_skus === 50 &&
        cleanRes.duplicates_in_paste === 0 &&
        cleanRes.foreign_skus === 0 &&
        cleanRes.missing_files.length === 0
    );

    results['URL_VALIDATION'] = isUrlSkuPass ? 'PASS' : 'FAIL';
    results['SKU_VALIDATION'] = isUrlSkuPass ? 'PASS' : 'FAIL';
    console.log(`  -> Valid: ${cleanRes.valid_meesho_links}, Matched: ${cleanRes.matched_skus}, Clean: ${cleanRes.is_clean}`);
    console.log(`  Result: ${results['URL_VALIDATION']}\n`);

    // 3. Test Duplicate Protection (51 URLs)
    console.log("[TEST 3] Duplicate URL Protection (50 + 1 duplicate)...");
    const dupUrl = `https://upload.meeshosupplyassets.com/cataloging/1789234567890/${batch01.filenames[0]}`;
    const dupText = valid50Urls + '\n' + dupUrl;

    const dupRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        rawText: dupText
    });

    const isDupPass = (
        dupRes.is_clean === false &&
        dupRes.duplicates_in_paste >= 1
    );

    results['DUPLICATE_PROTECTION'] = isDupPass ? 'PASS' : 'FAIL';
    console.log(`  -> Clean: ${dupRes.is_clean}, Duplicates Caught: ${dupRes.duplicates_in_paste}`);
    console.log(`  Result: ${results['DUPLICATE_PROTECTION']}\n`);

    // 4. Test Foreign SKU / Domain Protection
    console.log("[TEST 4] Foreign Domain & Foreign SKU Protection...");
    const foreignText = valid50Urls.replace(batch01.filenames[0], 'P-9999_1.jpg') + '\nhttps://malicious-site.com/image.jpg';
    const foreignRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        rawText: foreignText
    });

    const isForeignPass = (
        foreignRes.is_clean === false &&
        (foreignRes.foreign_skus >= 1 || foreignRes.missing_files.length >= 1) &&
        foreignRes.invalid_urls >= 1
    );
    console.log(`  -> Clean: ${foreignRes.is_clean}, Foreign/Missing: ${foreignRes.foreign_skus}/${foreignRes.missing_files.length}, Invalid URLs: ${foreignRes.invalid_urls}`);
    console.log(`  Foreign Protection: ${isForeignPass ? 'PASS' : 'FAIL'}\n`);

    // 5. Test Atomic State Save Integrity & Verification
    console.log("[TEST 5] Atomic State Save & Next Batch Progression Verification...");
    const linkState = JSON.parse(fs.readFileSync('scripts/meesho-image-link-state.json', 'utf8'));
    const batchState = JSON.parse(fs.readFileSync('scripts/meesho-image-batch-state.json', 'utf8'));

    const test50Batch = batchState.batches.find(b => b.id === 'Batch_TEST_50');
    const batch01State = batchState.batches.find(b => b.id === 'Batch_01');
    const nextReady = batchState.batches.find(b => b.status === 'READY');

    const isStatePass = (
        test50Batch && test50Batch.status === 'VERIFIED' &&
        batch01State && batch01State.status === 'READY' &&
        linkState.products && Object.keys(linkState.products).length > 0
    );

    const isNextSelectionPass = (nextReady && nextReady.id === 'Batch_01');

    results['ATOMIC_STATE_SAVE'] = isStatePass ? 'PASS' : 'FAIL';
    results['NEXT_BATCH_SELECTION'] = isNextSelectionPass ? 'PASS' : 'FAIL';
    results['LOCAL_WORKFLOW'] = (
        results['CURRENT_BATCH_DETECTION'] === 'PASS' &&
        results['URL_VALIDATION'] === 'PASS' &&
        results['SKU_VALIDATION'] === 'PASS' &&
        results['DUPLICATE_PROTECTION'] === 'PASS' &&
        results['ATOMIC_STATE_SAVE'] === 'PASS' &&
        results['NEXT_BATCH_SELECTION'] === 'PASS'
    ) ? 'PASS' : 'FAIL';

    console.log(`  -> Batch_TEST_50 Preserved: ${test50Batch.status === 'VERIFIED'}`);
    console.log(`  -> Batch_01 State Untouched: ${batch01State.status === 'READY'}`);
    console.log(`  -> Next Ready Batch: ${nextReady.id}`);
    console.log(`  Result Atomic State: ${results['ATOMIC_STATE_SAVE']}`);
    console.log(`  Result Next Batch Selection: ${results['NEXT_BATCH_SELECTION']}\n`);

    console.log("==================================================");
    console.log(" FINAL RESULTS MATRIX");
    console.log("==================================================");
    for (let k in results) {
        console.log(`${k} = ${results[k]}`);
    }
}

runTests().catch(console.error);
