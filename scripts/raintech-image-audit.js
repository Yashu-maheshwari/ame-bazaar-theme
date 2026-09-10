require('dotenv').config();
const https = require('https');
const fs = require('fs');

const wcOptions = {
    hostname: 'amebazaar.in',
    port: 443,
    auth: `${process.env.WC_CONSUMER_KEY}:${process.env.WC_CONSUMER_SECRET}`,
    headers: { 'User-Agent': 'Node.js' }
};

function fetchApi(path) {
    return new Promise((resolve, reject) => {
        let options = { ...wcOptions, method: 'GET', path };
        if (path.includes('wp/v2/media')) delete options.auth;
        const req = https.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                if (res.statusCode !== 200) return resolve({ data: [], totalPages: 0, total: 0 });
                const totalPages = parseInt(res.headers['x-wp-totalpages'] || '1', 10);
                const total = parseInt(res.headers['x-wp-total'] || '0', 10);
                try { resolve({ data: JSON.parse(data), totalPages, total }); } 
                catch(e) { resolve({ data: [], totalPages: 0, total: 0}); }
            });
        });
        req.on('error', reject);
        req.end();
    });
}

async function fetchAllProducts() {
    console.log("Fetching WooCommerce products...");
    let first = await fetchApi('/wp-json/wc/v3/products?per_page=100&status=publish&_fields=id,name,sku,images');
    let allData = [...first.data];
    let promises = [];
    for (let i = 2; i <= first.totalPages; i++) {
        promises.push(fetchApi(`/wp-json/wc/v3/products?per_page=100&status=publish&_fields=id,name,sku,images&page=${i}`).then(res => res.data));
        if (promises.length >= 10 || i === first.totalPages) {
            let results = await Promise.all(promises);
            results.forEach(arr => allData = allData.concat(arr));
            promises = [];
        }
    }
    return allData;
}

async function run() {
    let wcProducts = await fetchAllProducts();
    let missingImageProducts = wcProducts.filter(p => !p.images || p.images.length === 0);
    console.log(`Found ${wcProducts.length} total WC products. ${missingImageProducts.length} are missing images.`);

    console.log("Reading Raintech exported mappings...");
    let fileData = fs.readFileSync('scripts/raintech_export.csv', 'utf8');
    let lines = fileData.split(/\r?\n/);
    let raintechProducts = [];
    for(let i = 2; i < lines.length; i++) { // skip header and hyphens
        let line = lines[i].trim();
        if(line && !line.startsWith('(')) {
            let parts = line.split(',');
            if(parts.length >= 2) {
                raintechProducts.push({
                    ProductCode: parts[0].trim(),
                    ProductName: parts.slice(1).join(',').trim()
                });
            }
        }
    }

    console.log(`Found ${raintechProducts.length} Raintech products with BLOB photos.`);

    // Match them!
    let stats = {
        exact_sku_match: 0,
        exact_name_match: 0,
        no_match: 0,
        samples: []
    };

    let raintechByCode = {};
    let raintechByName = {};
    raintechProducts.forEach(rp => {
        if (rp.ProductCode) raintechByCode[rp.ProductCode.toLowerCase()] = rp;
        if (rp.ProductName) raintechByName[rp.ProductName.toLowerCase()] = rp;
    });

    for (let wp of missingImageProducts) {
        let wpSku = (wp.sku || '').trim().toLowerCase();
        let wpName = (wp.name || '').trim().toLowerCase();
        
        let match = null;
        let matchType = null;
        
        if (wpSku && raintechByCode[wpSku]) {
            match = raintechByCode[wpSku];
            matchType = 'EXACT_SKU';
            stats.exact_sku_match++;
        } else if (wpName && raintechByName[wpName]) {
            match = raintechByName[wpName];
            matchType = 'EXACT_NAME';
            stats.exact_name_match++;
        } else {
            stats.no_match++;
        }
        
        if (match && stats.samples.length < 20) {
            stats.samples.push({
                WC_ID: wp.id,
                WC_SKU: wp.sku,
                WC_Name: wp.name,
                Raintech_Code: match.ProductCode,
                Raintech_Name: match.ProductName,
                Match_Reason: matchType,
                Confidence: 'High'
            });
        }
    }

    console.log("\\n--- IMAGE AUDIT RESULTS ---");
    console.log("Total WC Products missing images:", missingImageProducts.length);
    console.log("Matched via EXACT SKU:", stats.exact_sku_match);
    console.log("Matched via EXACT NAME:", stats.exact_name_match);
    console.log("No Match Found:", stats.no_match);
    
    console.log("\\n--- 20 SAMPLE MATCHES ---");
    stats.samples.forEach(s => {
        console.log(`[WC ID: ${s.WC_ID}] [SKU: ${s.WC_SKU || 'N/A'}] ${s.WC_Name}  -->  [RT Code: ${s.Raintech_Code}] ${s.Raintech_Name} (Reason: ${s.Match_Reason}, Confidence: ${s.Confidence})`);
    });
}

run().catch(console.error);
