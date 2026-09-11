const fs = require('fs');
const https = require('https');
const path = require('path');

const wcOptions = {
    hostname: 'amebazaar.in',
    port: 443,
    auth: 'ck_787d6ed7345177b1492b0dacf67995e858bb053c:cs_76f69590461eace3163b94a4a1af111281d7ff08',
    headers: { 'User-Agent': 'Node.js' }
};

function fetchApi(apiPath) {
    return new Promise((resolve, reject) => {
        let options = { ...wcOptions, method: 'GET', path: apiPath };
        const req = https.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                try { resolve(JSON.parse(data)); }
                catch(e) { resolve([]); }
            });
        });
        req.on('error', reject);
        req.end();
    });
}

function loadJSON(file) {
    try { return JSON.parse(fs.readFileSync(file, 'utf8')); }
    catch(e) { return null; }
}

function mapCategory(wcCats) {
    if (!wcCats) return 'UNKNOWN';
    let text = wcCats.map(c => c.name.toLowerCase()).join(' ');
    if (text.includes('tshirt') || text.includes('t-shirt')) return 'TSHIRT';
    if (text.includes('frock') || text.includes('dress')) return 'FROCK';
    if (text.includes('set') || text.includes('suit') || text.includes('baba suit')) return 'CLOTHING_SET';
    if (text.includes('jean') || text.includes('trouser') || text.includes('pant') || text.includes('lower') || text.includes('capri')) return 'JEANS';
    return 'UNKNOWN';
}

async function run() {
    console.log("Loading local state...");
    const bp = loadJSON('scripts/meesho-business-profile.json') || { business_profile: {} };
    const matrix = loadJSON('meesho_template_field_matrix.json') || {};
    const profileState = loadJSON('scripts/meesho-profile-state.json') || { profiles: {} };
    
    // Map product ID to approved profile
    let productToProfile = {};
    for (let pid in profileState.profiles) {
        let prof = profileState.profiles[pid];
        if (prof.status === 'APPROVED') {
            for (let prodId of prof.product_ids) {
                productToProfile[prodId.toString()] = prof;
            }
        }
    }

    console.log("Fetching WooCommerce products (Limit to 2000 for speed)...");
    let products = [];
    let page = 1;
    while(true) {
        let batch = await fetchApi(`/wp-json/wc/v3/products?per_page=100&page=${page}`);
        if(!batch || batch.length === 0) break;
        products.push(...batch);
        page++;
        if (page > 20) break; // Limit 2000 for realistic audit timing
    }
    
    console.log(`Fetched ${products.length} products.`);

    let results = {
        total_products: products.length,
        ready: 0,
        partially_ready: 0,
        blocked: 0,
        category_unresolved: 0,
        products: []
    };

    let blockersCount = {};

    let whatIfCharts = {
        'TSHIRT': 0,
        'FROCK': 0,
        'CLOTHING_SET': 0,
        'JEANS': 0
    };

    for (let p of products) {
        let cat = mapCategory(p.categories);
        if (cat === 'UNKNOWN') {
            results.category_unresolved++;
            let blocker = "CATEGORY_UNRESOLVED";
            blockersCount[blocker] = (blockersCount[blocker] || 0) + 1;
            continue;
        }

        let missingFields = [];
        let satisfiedFields = [];
        
        // Identity
        if (!p.sku) missingFields.push("Missing SKU");
        else satisfiedFields.push("SKU");
        
        // Pricing
        let sp = parseFloat(p.price);
        let mrp = parseFloat(p.regular_price || p.price);
        if (!sp || sp <= 0) missingFields.push("Missing Selling Price");
        else satisfiedFields.push("Selling Price");
        
        if (!mrp || mrp <= 0 || mrp < sp) missingFields.push("Missing/Invalid MRP");
        else satisfiedFields.push("MRP");

        // Compliance
        let profileInfo = bp.business_profile || {};
        if (!profileInfo.manufacturer_name) missingFields.push("Missing Manufacturer Name");
        if (!profileInfo.packer_name) missingFields.push("Missing Packer Name");
        if (!profileInfo.country_of_origin) missingFields.push("Missing Country of Origin");

        // Attributes (from approved profiles)
        let prof = productToProfile[p.id.toString()];
        let hasApprovedAttrs = prof && Object.keys(prof.approved_attributes).length > 0;
        
        let requiredAttrs = [];
        if (cat === 'TSHIRT') requiredAttrs = ['Color', 'Fabric', 'Neck', 'Pattern', 'Sleeve Length'];
        else if (cat === 'FROCK') requiredAttrs = ['Color', 'Fabric', 'Pattern'];
        else if (cat === 'CLOTHING_SET') requiredAttrs = ['Top Fabric', 'Bottom Fabric', 'Color'];
        else if (cat === 'JEANS') requiredAttrs = ['Fabric', 'Color', 'Pattern'];

        if (!hasApprovedAttrs) {
            missingFields.push(`Missing Attributes (${requiredAttrs.join(', ')})`);
        } else {
            satisfiedFields.push("Attributes (Partial/Full)");
        }

        // Measurements
        missingFields.push("Missing Verified Measurement Chart");

        // Images
        let hasMeeshoImage = false; // Always false currently
        missingFields.push("Missing Official Meesho Image URL");

        // Determine Status
        let status = 'BLOCKED';
        if (missingFields.length === 0) status = 'READY_FOR_MEESHO_EXPORT';
        else if (missingFields.length <= 2) status = 'PARTIALLY_READY';
        else status = 'BLOCKED';

        if (status === 'READY_FOR_MEESHO_EXPORT') results.ready++;
        else if (status === 'PARTIALLY_READY') results.partially_ready++;
        else results.blocked++;

        // Track blockers
        for (let b of missingFields) {
            let key = `${b} [${cat}]`;
            blockersCount[key] = (blockersCount[key] || 0) + 1;
        }

        // What if calculations (If only Measurements & Images are missing, would it be ready?)
        let missingOtherThanChartsAndImages = missingFields.filter(m => m !== "Missing Verified Measurement Chart" && m !== "Missing Official Meesho Image URL");
        if (missingOtherThanChartsAndImages.length === 0) {
            whatIfCharts[cat]++;
        }
    }

    // Grouping blockers
    let topBlockers = Object.keys(blockersCount).map(k => {
        let parts = k.split(' [');
        let field = parts[0];
        let cat = parts[1] ? parts[1].replace(']', '') : 'ANY';
        return {
            blocker: field,
            category: cat,
            count: blockersCount[k],
            automation_possible: field.includes("Measurement") ? "Yes (Size Chart Batching)" : (field.includes("Image") ? "No (Manual Panel Upload Required)" : "Yes (Attribute Batching)")
        };
    }).sort((a,b) => b.count - a.count).slice(0, 10);

    fs.writeFileSync('meesho_preexport_readiness.json', JSON.stringify(results, null, 2));
    fs.writeFileSync('meesho_blocker_groups.json', JSON.stringify({ top_blockers: topBlockers }, null, 2));

    let md = `# Meesho Pre-Export Readiness Report

## Executive Summary
* **Total Products Evaluated:** ${results.total_products}
* **Ready for Meesho Export:** ${results.ready}
* **Partially Ready:** ${results.partially_ready}
* **Blocked:** ${results.blocked}
* **Category Unresolved:** ${results.category_unresolved}

## Top 10 Blockers (Batch Groupings)
| Blocker Field | Category | Products Affected | Automation Possible | Minimum Human Action |
|---------------|----------|-------------------|---------------------|----------------------|
`;
    topBlockers.forEach(b => {
        let action = b.blocker.includes('Image') ? 'Upload local images to Supplier Panel & paste URLs' :
                     b.blocker.includes('Measurement') ? 'Create 1 Verified Size Chart & click Assign' :
                     b.blocker.includes('Attribute') ? 'Click Approve on Pre-filled Profiles in Batch UI' : 'Fix Business Profile JSON';
        md += `| ${b.blocker} | ${b.category} | ${b.count} | ${b.automation_possible} | ${action} |\n`;
    });

    md += `\n## 'What-If' Size Chart Impact\n`;
    md += `If you added **one verified size chart** per category AND pasted **official image links**, this many products would instantly become READY_FOR_EXPORT:\n`;
    md += `* **T-Shirts:** ${whatIfCharts.TSHIRT} products\n`;
    md += `* **Frocks:** ${whatIfCharts.FROCK} products\n`;
    md += `* **Clothing Sets:** ${whatIfCharts.CLOTHING_SET} products\n`;
    md += `* **Jeans:** ${whatIfCharts.JEANS} products\n`;

    fs.writeFileSync('meesho_preexport_readiness_report.md', md);

    let nextActionsMd = `# Meesho Next Actions

### SINGLE HIGHEST-IMPACT BLOCKER
**Missing Official Meesho Image URLs** - Affects 100% of the catalog. The official Meesho .xlsx templates explicitly block Google Drive or WooCommerce URLs. All images must be manually generated through the Meesho Supplier Panel Image Upload tool.

### SINGLE BEST NEXT ACTION
**Create the "Verified Size Chart Profile" via the Batch UI.** Once standard measurement charts are created, we can programmatically bind them to the products, clearing the measurement blocker completely. Afterward, the ONLY remaining manual step is the Image URL copy-pasting.
`;
    fs.writeFileSync('meesho_next_actions.md', nextActionsMd);

    console.log("Readiness engine execution complete.");
}

run();
