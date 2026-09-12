const fs = require('fs');
const path = require('path');
const AdmZip = require('adm-zip');

const ZIP_DIR = path.join(__dirname, 'meesho_image_export_package', 'zips');
const BATCH_STATE_FILE = path.join(__dirname, 'meesho-image-batch-state.json');
const REPORT_FILE = path.join(__dirname, 'meesho_image_batch_package_verification.md');

function run() {
    let batchState = JSON.parse(fs.readFileSync(BATCH_STATE_FILE, 'utf8'));
    
    let md = `# Meesho Image Batch Package Verification Report\n\n`;
    md += `| Batch ID | ZIP Filename | Expected Images | Actual Images | ZIP Size (KB) | Status |\n`;
    md += `|----------|--------------|-----------------|---------------|---------------|--------|\n`;

    let totalExpected = 0;
    let totalActual = 0;
    let allPass = true;
    let seenFilenames = new Set();
    let duplicates = 0;

    batchState.batches.forEach(b => {
        // Skip already-verified batches (ZIP may have been deleted after successful capture)
        if (b.status === 'VERIFIED') {
            md += `| ${b.id} | ${b.zip_filename} | ${b.image_count} | — | — | ✅ ALREADY VERIFIED |\n`;
            // Still track their filenames for cross-batch duplicate detection
            b.filenames.forEach(f => seenFilenames.add(f));
            return;
        }

        let zipPath = path.join(ZIP_DIR, b.zip_filename);
        let exists = fs.existsSync(zipPath);
        
        let expectedCount = b.image_count;
        totalExpected += expectedCount;
        
        let actualCount = 0;
        let sizeKb = 0;
        let pass = false;
        let errors = [];

        if (exists) {
            let stats = fs.statSync(zipPath);
            sizeKb = Math.round(stats.size / 1024);
            
            try {
                let zip = new AdmZip(zipPath);
                let zipEntries = zip.getEntries();
                actualCount = zipEntries.length;
                totalActual += actualCount;
                
                // Verify filenames
                let entryNames = zipEntries.map(e => e.entryName);
                b.filenames.forEach(f => {
                    if (!entryNames.includes(f)) {
                        errors.push(`Missing file: ${f}`);
                    }
                });

                entryNames.forEach(f => {
                    if (!b.filenames.includes(f)) {
                        errors.push(`Unexpected file: ${f}`);
                    }
                    if (seenFilenames.has(f)) {
                        duplicates++;
                        errors.push(`Duplicate file across batches: ${f}`);
                    }
                    seenFilenames.add(f);
                });

                if (actualCount === expectedCount && errors.length === 0) {
                    pass = true;
                }
            } catch (e) {
                errors.push(`Invalid ZIP: ${e.message}`);
            }
        } else {
            errors.push('ZIP file physically missing.');
        }

        if (!pass) allPass = false;
        let statusText = pass ? '✅ PASS' : `❌ FAIL (${errors.join(', ')})`;
        
        md += `| ${b.id} | ${b.zip_filename} | ${expectedCount} | ${actualCount} | ${sizeKb} | ${statusText} |\n`;
    });

    md += `\n## Summary\n`;
    md += `- **Total Expected Images:** ${totalExpected}\n`;
    md += `- **Total Actual Packaged Images:** ${totalActual}\n`;
    md += `- **Total Duplicate Images:** ${duplicates}\n`;
    md += `- **Overall Verification:** ${allPass ? '✅ PASS' : '❌ FAIL'}\n`;

    fs.writeFileSync(REPORT_FILE, md);
    console.log(`Verification complete. Output saved to ${REPORT_FILE}`);
    console.log(`PASS: ${allPass}, Expected: ${totalExpected}, Actual: ${totalActual}`);
}

run();
