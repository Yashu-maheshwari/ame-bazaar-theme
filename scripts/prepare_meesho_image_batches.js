const fs = require('fs');
const path = require('path');
const AdmZip = require('adm-zip');

const EXPORT_DIR = path.join(__dirname, 'meesho_image_export_package');
const ZIP_DIR = path.join(EXPORT_DIR, 'zips');
const BATCH_STATE_FILE = path.join(__dirname, 'meesho-image-batch-state.json');
const MANIFEST_FILE = path.join(__dirname, 'meesho_image_manifest.json');
const LINK_STATE_FILE = path.join(__dirname, 'meesho-image-link-state.json');
const BATCH_SIZE = 50;

function run() {
    // 1. Load all sources of truth
    let manifest = JSON.parse(fs.readFileSync(MANIFEST_FILE, 'utf8'));
    let linkState = JSON.parse(fs.readFileSync(LINK_STATE_FILE, 'utf8'));
    let oldBatchState = JSON.parse(fs.readFileSync(BATCH_STATE_FILE, 'utf8'));

    // 2. Identify Batch_TEST_50 (VERIFIED) — preserve it
    let testBatch = oldBatchState.batches.find(b => b.id === 'Batch_TEST_50');
    if (!testBatch || testBatch.status !== 'VERIFIED') {
        throw new Error('Batch_TEST_50 must exist and be VERIFIED before repartitioning.');
    }

    // 3. Build set of ALL already-verified filenames
    let verifiedFilenames = new Set();

    // From audit_log (includes initial 9 -Copy files AND Batch_TEST_50's 50 files)
    if (linkState.audit_log) {
        linkState.audit_log.forEach(log => {
            if (log.filename) {
                verifiedFilenames.add(log.filename);
                // Also add the non-Copy variant in case the manifest uses it
                verifiedFilenames.add(log.filename.replace('-Copy', ''));
            }
        });
    }

    // Also explicitly add Batch_TEST_50 filenames
    testBatch.filenames.forEach(f => verifiedFilenames.add(f));

    console.log(`Verified filenames to exclude: ${verifiedFilenames.size}`);

    // 4. Filter manifest to only non-missing, non-verified files
    let allValid = manifest.filter(f => f.status !== 'MISSING' && f.export_filename);
    let remaining = allValid.filter(f => !verifiedFilenames.has(f.export_filename));

    // Verify no duplicates in remaining
    let remainingSet = new Set(remaining.map(f => f.export_filename));
    if (remainingSet.size !== remaining.length) {
        throw new Error(`Duplicate export_filenames detected! Set: ${remainingSet.size}, Array: ${remaining.length}`);
    }

    console.log(`Total valid manifest files: ${allValid.length}`);
    console.log(`Remaining unverified files: ${remaining.length}`);

    // 5. Sort deterministically
    remaining.sort((a, b) => a.export_filename.localeCompare(b.export_filename));

    // 6. Chunk into batches of 50
    let batches = [];
    for (let i = 0; i < remaining.length; i += BATCH_SIZE) {
        let chunk = remaining.slice(i, i + BATCH_SIZE);
        let batchNum = Math.floor(i / BATCH_SIZE) + 1;
        let paddedNum = String(batchNum).padStart(2, '0');
        batches.push({
            id: `Batch_${paddedNum}`,
            zip_filename: `Batch_${paddedNum}.zip`,
            status: 'READY',
            sku_count: chunk.length,
            image_count: chunk.length,
            skus: chunk.map(f => f.sku),
            filenames: chunk.map(f => f.export_filename),
            captured_sku_count: 0,
            duplicate_count: 0,
            unknown_count: 0,
            invalid_url_count: 0
        });
    }

    console.log(`Total new batches: ${batches.length}`);
    console.log(`Last batch image count: ${batches[batches.length - 1].image_count}`);

    // 7. Delete old ZIP files (only .zip files, not directories)
    if (fs.existsSync(ZIP_DIR)) {
        let oldFiles = fs.readdirSync(ZIP_DIR);
        let deleted = 0;
        oldFiles.forEach(f => {
            let fp = path.join(ZIP_DIR, f);
            if (f.endsWith('.zip') && fs.statSync(fp).isFile()) {
                fs.unlinkSync(fp);
                deleted++;
            }
        });
        console.log(`Deleted ${deleted} old ZIP files.`);
    } else {
        fs.mkdirSync(ZIP_DIR, { recursive: true });
    }

    // 8. Create new ZIP files
    let totalPackaged = 0;
    batches.forEach((batch, idx) => {
        let zip = new AdmZip();
        batch.filenames.forEach(f => {
            let imgPath = path.join(EXPORT_DIR, f);
            if (!fs.existsSync(imgPath)) {
                throw new Error(`Image file missing: ${imgPath}`);
            }
            zip.addLocalFile(imgPath);
        });
        zip.writeZip(path.join(ZIP_DIR, batch.zip_filename));
        totalPackaged += batch.image_count;
        if ((idx + 1) % 10 === 0 || idx === batches.length - 1) {
            console.log(`  Created ${batch.zip_filename} (${batch.image_count} images)`);
        }
    });

    console.log(`Total images packaged: ${totalPackaged}`);

    // 9. Save new batch state (Batch_TEST_50 first, then new batches)
    let newBatchState = {
        batches: [testBatch, ...batches]
    };
    fs.writeFileSync(BATCH_STATE_FILE, JSON.stringify(newBatchState, null, 2));
    console.log(`Batch state saved with ${newBatchState.batches.length} total entries (1 VERIFIED + ${batches.length} READY).`);

    // 10. Cross-batch duplicate check
    let allFilenames = new Set();
    let duplicateCount = 0;
    newBatchState.batches.forEach(b => {
        b.filenames.forEach(f => {
            if (allFilenames.has(f)) {
                duplicateCount++;
                console.error(`DUPLICATE across batches: ${f}`);
            }
            allFilenames.add(f);
        });
    });
    console.log(`Cross-batch duplicate check: ${duplicateCount} duplicates found.`);
    console.log(`Total unique filenames across all batches (incl. TEST_50): ${allFilenames.size}`);
}

run();
