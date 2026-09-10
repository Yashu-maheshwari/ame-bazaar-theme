require('dotenv').config({path: 'scripts/.env'});
const https = require('https');
const WC_URL = process.env.WC_URL || 'https://amebazaar.in';
const WC_KEY = process.env.WC_CONSUMER_KEY;
const WC_SECRET = process.env.WC_CONSUMER_SECRET;

async function runAudit() {
    let page = 1;
    let total = 0;
    let withImage = 0;
    let missingImage = 0;
    
    while(true) {
        const res = await new Promise(resolve => {
            const options = {
                hostname: new URL(WC_URL).hostname,
                path: `/wp-json/wc/v3/products?per_page=100&status=publish&page=${page}&_fields=id,images`,
                auth: `${WC_KEY}:${WC_SECRET}`,
                headers: {'User-Agent': 'Node.js'}
            };
            https.get(options, r => {
                let d = '';
                r.on('data', c => d+=c);
                r.on('end', () => resolve({status: r.statusCode, data: JSON.parse(d)}));
            });
        });
        
        if (res.status !== 200 || !Array.isArray(res.data) || res.data.length === 0) break;
        
        total += res.data.length;
        for (const p of res.data) {
            if (p.images && p.images.length > 0) withImage++;
            else missingImage++;
        }
        page++;
    }
    
    const pct = ((withImage / total) * 100).toFixed(2);
    console.log(`TOTAL_PUBLISHED:${total}`);
    console.log(`WITH_IMAGE:${withImage}`);
    console.log(`MISSING_IMAGE:${missingImage}`);
    console.log(`RECOVERY_PCT:${pct}%`);
}

runAudit();
