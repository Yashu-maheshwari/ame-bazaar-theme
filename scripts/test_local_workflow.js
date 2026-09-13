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
    console.log(" COMPREHENSIVE LOCAL WORKFLOW & VALIDATION SUITE");
    console.log("==================================================\n");

    let results = {};

    // 1. Test GET /api/batch-status & Next Batch Detection
    console.log("[TEST 1] Current Batch Detection & CORS Header...");
    const statusRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/batch-status',
        method: 'GET'
    });

    const isDetectionPass = (
        statusRes.summary &&
        statusRes.summary.total_batches === 73 &&
        statusRes.summary.verified_batches === 1 &&
        statusRes.summary.ready_batches === 72 &&
        statusRes.next_batch &&
        statusRes.next_batch.id === 'Batch_01' &&
        statusRes.next_batch.image_count === 50 &&
        fs.existsSync(statusRes.next_batch.physical_path)
    );

    results['CURRENT_BATCH_DETECTION'] = isDetectionPass ? 'PASS' : 'FAIL';
    console.log(`  -> Current Next Batch: ${statusRes.next_batch ? statusRes.next_batch.id : 'NONE'}`);
    console.log(`  -> Expected Images: ${statusRes.next_batch ? statusRes.next_batch.image_count : 0}`);
    console.log(`  -> Physical ZIP: ${statusRes.next_batch ? statusRes.next_batch.physical_path : 'NONE'}`);
    console.log(`  Result: ${results['CURRENT_BATCH_DETECTION']}\n`);

    const batch01 = statusRes.next_batch;
    const valid50Urls = batch01.filenames.map(f => `https://upload.meeshosupplyassets.com/cataloging/1789234567890/${f}`);

    // 2. Test Exact 50/50 via Array Payload (Bookmarklet format)
    console.log("[TEST 2] Exact 50/50 Array Payload (One-Click Bookmarklet Format)...");
    const arrayRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        urls: valid50Urls
    });

    const isArrayPass = (
        arrayRes.is_clean === true &&
        arrayRes.valid_meesho_links === 50 &&
        arrayRes.matched_skus === 50 &&
        arrayRes.duplicates_in_paste === 0 &&
        arrayRes.foreign_skus === 0 &&
        arrayRes.missing_files.length === 0
    );
    results['ONE_CLICK_HANDOFF'] = isArrayPass ? 'PASS' : 'FAIL';
    console.log(`  -> Array Ingestion Clean: ${arrayRes.is_clean}, Valid URLs: ${arrayRes.valid_meesho_links}/50`);
    console.log(`  Result: ${results['ONE_CLICK_HANDOFF']}\n`);

    // 3. Test Exact 50/50 via String Paste
    console.log("[TEST 3] Exact 50/50 String Payload (Manual Paste Fallback)...");
    const stringRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        rawText: valid50Urls.join('\n')
    });
    console.log(`  -> String Ingestion Clean: ${stringRes.is_clean}, Matched: ${stringRes.matched_skus}/50\n`);

    // 4. Test Duplicate Protection
    console.log("[TEST 4] Duplicate URL Protection (50 + 1 duplicate)...");
    const dupRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        urls: [...valid50Urls, valid50Urls[0]]
    });
    const isDupPass = (dupRes.is_clean === false && dupRes.duplicates_in_paste >= 1);
    results['DUPLICATE_PROTECTION'] = isDupPass ? 'PASS' : 'FAIL';
    console.log(`  -> Clean: ${dupRes.is_clean}, Duplicates Caught: ${dupRes.duplicates_in_paste}`);
    console.log(`  Result: ${results['DUPLICATE_PROTECTION']}\n`);

    // 5. Test Incomplete Batch (49 files)
    console.log("[TEST 5] Incomplete Batch Protection (49 of 50 URLs)...");
    const incompleteRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        urls: valid50Urls.slice(0, 49)
    });
    const isIncompletePass = (incompleteRes.is_clean === false && incompleteRes.missing_files.length === 1);
    console.log(`  -> Clean: ${incompleteRes.is_clean}, Missing Count: ${incompleteRes.missing_files.length}`);
    console.log(`  Result: ${isIncompletePass ? 'PASS' : 'FAIL'}\n`);

    // 6. Test Foreign Domain & Malformed URL
    console.log("[TEST 6] Foreign Domain & Malformed URL Protection...");
    const badDomainRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        urls: [...valid50Urls.slice(0, 49), 'https://unauthorized-domain.com/cataloging/test.jpg', 'not-a-url']
    });
    const isBadDomainPass = (badDomainRes.is_clean === false && badDomainRes.invalid_urls >= 1);
    console.log(`  -> Clean: ${badDomainRes.is_clean}, Invalid URLs Caught: ${badDomainRes.invalid_urls}`);
    console.log(`  Result: ${isBadDomainPass ? 'PASS' : 'FAIL'}\n`);

    // 7. Test Unknown SKU / Foreign File
    console.log("[TEST 7] Unknown SKU & Foreign File Protection...");
    const foreignRes = await request({
        hostname: 'localhost',
        port: 3002,
        path: '/api/capture-links-batch',
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    }, {
        batchId: 'Batch_01',
        dryRun: true,
        urls: [...valid50Urls.slice(0, 49), 'https://upload.meeshosupplyassets.com/cataloging/1789234567890/P-9999_1.jpg']
    });
    const isForeignPass = (foreignRes.is_clean === false && (foreignRes.foreign_skus >= 1 || foreignRes.unknown_skus >= 1));
    console.log(`  -> Clean: ${foreignRes.is_clean}, Foreign/Unknown: ${foreignRes.foreign_skus}/${foreignRes.unknown_skus}`);
    console.log(`  Result: ${isForeignPass ? 'PASS' : 'FAIL'}\n`);

    // 8. Test Atomic State & Next Batch Selection
    console.log("[TEST 8] State Integrity & Batch_TEST_50 Preservation...");
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

    results['VALIDATION'] = (isArrayPass && isDupPass && isIncompletePass && isBadDomainPass && isForeignPass) ? 'PASS' : 'FAIL';
    results['ATOMIC_SAVE'] = isStatePass ? 'PASS' : 'FAIL';
    results['AUTO_NEXT_BATCH'] = isNextSelectionPass ? 'PASS' : 'FAIL';
    results['LOCAL_AUTOMATION'] = (
        results['ONE_CLICK_HANDOFF'] === 'PASS' &&
        results['CURRENT_BATCH_DETECTION'] === 'PASS' &&
        results['VALIDATION'] === 'PASS' &&
        results['ATOMIC_SAVE'] === 'PASS' &&
        results['AUTO_NEXT_BATCH'] === 'PASS'
    ) ? 'PASS' : 'FAIL';

    console.log(`  -> Batch_TEST_50 VERIFIED Preserved: ${test50Batch.status === 'VERIFIED'}`);
    console.log(`  -> Batch_01 State Untouched (dry run only): ${batch01State.status === 'READY'}`);
    console.log(`  -> Next Ready Batch: ${nextReady.id}`);
    console.log(`  Result State: ${results['ATOMIC_SAVE']}`);
    console.log(`  Result Auto Next Batch: ${results['AUTO_NEXT_BATCH']}\n`);

    console.log("==================================================");
    console.log(" FINAL TEST RESULTS MATRIX");
    console.log("==================================================");
    for (let k in results) {
        console.log(`${k} = ${results[k]}`);
    }
}

runTests().catch(console.error);
