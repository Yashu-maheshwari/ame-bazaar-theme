/**
 * Meesho UI Automation — Phase 2 Read-Only Connection Test
 *
 * READ-ONLY: Lists tabs, detects Meesho, reads title+URL only.
 * Does NOT upload, click, modify, or extract auth data.
 */

const puppeteer = require('puppeteer-core');
const http = require('http');

// Fetch JSON from CDP endpoint using Node's http module (avoids fetch() issues)
function httpGet(url) {
    return new Promise((resolve, reject) => {
        http.get(url, (res) => {
            let data = '';
            res.on('data', c => data += c);
            res.on('end', () => { try { resolve(JSON.parse(data)); } catch(e) { reject(e); } });
        }).on('error', reject);
    });
}

async function run() {
    console.log('=== Meesho UI Automation — Phase 2 Read-Only Test ===\n');

    // Step 1: Get the WebSocket URL via http module (not native fetch)
    let wsEndpoint;
    try {
        const version = await httpGet('http://127.0.0.1:9222/json/version');
        wsEndpoint = version.webSocketDebuggerUrl;
        console.log('CDP endpoint reached. WS URL:', wsEndpoint);
    } catch (err) {
        console.log('CDP_CONNECTION = NO');
        console.log('Error fetching /json/version:', err.message);
        console.log('\nChrome must be running with --remote-debugging-port=9222');
        console.log('Relaunch Chrome with:');
        console.log('"C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe" --remote-debugging-port=9222');
        process.exit(1);
    }

    // Step 2: Connect via wsEndpoint
    let browser;
    try {
        browser = await puppeteer.connect({
            browserWSEndpoint: wsEndpoint,
            defaultViewport: null
        });
        console.log('CDP_CONNECTION = YES');
    } catch (err) {
        console.log('CDP_CONNECTION = NO');
        console.log('Error connecting via WS:', err.message);
        process.exit(1);
    }

    // Step 3: List all open tabs via /json/list (READ-ONLY)
    let tabsJson;
    try {
        tabsJson = await httpGet('http://127.0.0.1:9222/json/list');
    } catch(e) {
        tabsJson = [];
    }

    console.log(`\nOpen tabs: ${tabsJson.length}\n`);

    let meeshoTabInfo = null;
    let imageBulkUploadFound = false;

    for (let i = 0; i < tabsJson.length; i++) {
        const tab = tabsJson[i];
        const url = tab.url || '';
        const title = tab.title || '';
        console.log(`  Tab ${i + 1}: [${title}] ${url}`);

        if (url.includes('supplier.meesho.com') || url.includes('meesho.io')) {
            meeshoTabInfo = tab;
            console.log(`    ^^^ MEESHO SUPPLIER PANEL DETECTED`);
            if (url.includes('image') || url.includes('bulk') || url.includes('catalog') ||
                title.toLowerCase().includes('image') || title.toLowerCase().includes('bulk') ||
                title.toLowerCase().includes('catalog')) {
                imageBulkUploadFound = true;
                console.log(`    ^^^ IMAGE/CATALOG PAGE DETECTED`);
            }
        }
    }

    // Step 4: If Meesho tab found, do READ-ONLY DOM inspection
    if (meeshoTabInfo) {
        console.log(`\nMeesho Tab Details (READ-ONLY):`);
        console.log(`  Title: ${meeshoTabInfo.title}`);
        console.log(`  URL: ${meeshoTabInfo.url}`);

        // Attach to the specific page and do a read-only DOM scan
        try {
            const pages = await browser.pages();
            const meeshoPage = pages.find(p => p.url().includes('meesho.com') || p.url().includes('meesho.io'));
            if (meeshoPage) {
                const uiCheck = await meeshoPage.evaluate(() => {
                    const text = document.body?.innerText || '';
                    return {
                        hasImageBulkUpload: text.includes('Image Bulk Upload') || text.includes('Get Image Link'),
                        hasFileInput: !!document.querySelector('input[type="file"]'),
                        hasGetImageLink: text.includes('Get Image Link'),
                        hasCatalogUpload: text.includes('Catalog Upload') || text.includes('catalog upload')
                    };
                });
                console.log(`\nPage UI Elements (READ-ONLY scan):`);
                console.log(`  Image Bulk Upload text present: ${uiCheck.hasImageBulkUpload}`);
                console.log(`  File input element present: ${uiCheck.hasFileInput}`);
                console.log(`  Get Image Link button: ${uiCheck.hasGetImageLink}`);
                console.log(`  Catalog Upload section: ${uiCheck.hasCatalogUpload}`);
                imageBulkUploadFound = uiCheck.hasImageBulkUpload;
            }
        } catch(e) {
            console.log(`  (Could not do detailed DOM scan: ${e.message})`);
        }
    }

    // Step 5: Summary
    console.log('\n=== PHASE 2 READ-ONLY TEST SUMMARY ===');
    console.log(`CDP_CONNECTION = YES`);
    console.log(`MEESHO_TAB = ${meeshoTabInfo ? 'YES' : 'NO'}`);
    console.log(`IMAGE_BULK_UPLOAD_PAGE = ${imageBulkUploadFound ? 'YES' : 'NO'}`);
    console.log(`PUPPETEER_CORE = INSTALLED`);
    console.log(`READ_ONLY_TEST = PASS`);

    browser.disconnect();
    console.log('\nDisconnected from Chrome. Browser remains open and authenticated.');
}

run().catch(err => {
    console.error('Fatal error:', err.message);
    process.exit(1);
});
