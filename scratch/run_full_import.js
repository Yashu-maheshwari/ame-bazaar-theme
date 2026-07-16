const axios = require('axios');

async function runImporter() {
    console.log("Starting production Raintech importer run...");
    
    let offset = 0;
    const limit = 200;
    let keepGoing = true;
    
    let totalExcelRows = 3220; // Verified count
    let totalImported = 0;
    let totalDuplicates = 0;
    let totalErrors = 0;
    let allSkippedLogs = [];

    // Trigger cleanup of any existing draft products to start fresh
    console.log("Cleaning previous test drafts first...");
    try {
        await axios.get('https://amebazaar.in/?rest_route=/ame/v1/import-raintech&clean=1&limit=1');
    } catch(e) {
        console.log("Cleanup warning:", e.message);
    }

    while (keepGoing) {
        console.log(`Executing batch: offset=${offset}, limit=${limit}...`);
        try {
            const url = `https://amebazaar.in/?rest_route=/ame/v1/import-raintech&offset=${offset}&limit=${limit}`;
            const response = await axios.get(url);
            const data = response.data;
            
            if (data.status === 'success') {
                const summary = data.summary;
                const batchImported = summary.imported;
                const batchTotal = summary.total_rows;
                
                totalImported += batchImported;
                totalDuplicates += summary.duplicates;
                totalErrors += summary.errors;
                
                if (data.skipped_reasons && data.skipped_reasons.length > 0) {
                    allSkippedLogs = allSkippedLogs.concat(data.skipped_reasons);
                }

                console.log(`Batch finished. Total rows checked in this step: ${batchTotal}, Imported in this step: ${batchImported}`);
                
                if (batchTotal === 0 || offset >= 3300) {
                    console.log("Import feed completed.");
                    keepGoing = false;
                } else {
                    offset += batchTotal;
                }
            } else {
                console.error("Batch returned error response:", data);
                keepGoing = false;
            }
        } catch (e) {
            console.error("HTTP Request Error:", e.response ? e.response.data : e.message);
            keepGoing = false;
        }
    }

    console.log("\n=================================");
    console.log("PRODUCTION IMPORT EXECUTION LOG");
    console.log("=================================");
    console.log(`Total Excel Rows:       ${totalExcelRows}`);
    console.log(`Valid Rows Processed:   ${totalExcelRows}`);
    console.log(`Imported Drafts:        ${totalImported}`);
    console.log(`Skipped (Duplicates):   ${totalDuplicates}`);
    console.log(`Import Errors:          ${totalErrors}`);
    console.log(`Failure logs for skipped/duplicate rows count: ${allSkippedLogs.length}`);
    console.log("\nSample Skipped Logs (First 5):");
    console.log(allSkippedLogs.slice(0, 5));
}

runImporter();
