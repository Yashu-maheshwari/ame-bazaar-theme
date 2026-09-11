const https = require('https');
const fs = require('fs');

const STATE_FILE = 'scripts/meesho-profile-state.json';

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
                if(res.statusCode >= 200 && res.statusCode < 300) {
                    resolve(JSON.parse(data));
                } else {
                    resolve([]); // fail gracefully
                }
            });
        });
        req.on('error', reject);
        req.end();
    });
}

const colors = ["Red", "Blue", "Black", "White", "Green", "Yellow", "Pink", "Purple", "Orange", "Grey", "Brown", "Navy", "Maroon", "Mustard"];
const fabrics = ["Cotton", "Denim", "Silk", "Polyester", "Rayon", "Linen", "Georgette", "Chiffon", "Crepe", "Net", "Velvet", "Wool", "Fleece", "Lycra"];
const patterns = ["Solid", "Printed", "Striped", "Checked", "Floral", "Embroidered", "Colorblocked", "Geometric"];

function findAllMatches(text, list) {
    if(!text) return [];
    let t = text.toLowerCase();
    let matches = [];
    for(let val of list) {
        if(t.includes(val.toLowerCase())) matches.push(val);
    }
    return matches;
}

function mapCategory(wcCats) {
    let text = wcCats.map(c => c.name.toLowerCase()).join(' ');
    if (text.includes('tshirt') || text.includes('t-shirt')) return 'TSHIRT';
    if (text.includes('frock') || text.includes('dress')) return 'FROCK';
    if (text.includes('set') || text.includes('suit') || text.includes('baba suit')) return 'CLOTHING_SET';
    if (text.includes('jean') || text.includes('trouser') || text.includes('pant') || text.includes('lower') || text.includes('capri')) return 'JEANS';
    return 'UNKNOWN';
}

async function run() {
    console.log("Fetching WooCommerce products for Validation...");
    let products = {};
    let page = 1;
    while(true) {
        let batch = await fetchApi(`/wp-json/wc/v3/products?per_page=100&page=${page}`);
        if(batch.length === 0) break;
        batch.forEach(p => { products[p.id.toString()] = p; });
        page++;
        if(page > 20) break; // limiting to original run size to match
    }
    console.log(`Fetched ${Object.keys(products).length} products.`);

    const state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    let profiles = state.profiles;

    let productProfileMap = {};
    let results = {
        total_profiles: Object.keys(profiles).length,
        SAFE_TO_APPROVE: 0,
        NEEDS_REVIEW: 0,
        INVALID: 0,
        products_safe: 0,
        products_needs_review: 0,
        products_invalid: 0,
        conflicting_skus: [],
        duplicate_assignments: [],
        profile_details: []
    };

    for (let pid in profiles) {
        let profile = profiles[pid];
        let status = 'SAFE_TO_APPROVE';
        let reasons = [];
        
        let pColor = profile.attributes.color.value !== 'HUMAN_REQUIRED' ? profile.attributes.color.value : null;
        let pFabric = profile.attributes.fabric.value !== 'HUMAN_REQUIRED' ? profile.attributes.fabric.value : null;
        let pPattern = profile.attributes.pattern.value !== 'HUMAN_REQUIRED' ? profile.attributes.pattern.value : null;

        if (profile.product_count !== profile.product_ids.length) {
            status = 'INVALID';
            reasons.push("Product count mismatch in state");
        }

        for (let prodId of profile.product_ids) {
            let idStr = prodId.toString();
            
            // Check duplicates
            if (productProfileMap[idStr]) {
                status = 'INVALID';
                reasons.push(`Product ${prodId} assigned to multiple profiles`);
                results.duplicate_assignments.push(prodId);
            }
            productProfileMap[idStr] = pid;

            let sourceProduct = products[idStr];
            if (!sourceProduct) {
                status = 'INVALID';
                reasons.push(`Product ${prodId} not found in source data`);
                continue;
            }

            let sourceCat = mapCategory(sourceProduct.categories);
            if (sourceCat !== profile.category) {
                status = 'INVALID';
                reasons.push(`Category mismatch for ${sourceProduct.sku}: Source=${sourceCat}, Profile=${profile.category}`);
            }

            let textSource = `${sourceProduct.name} ${sourceProduct.short_description} ${sourceProduct.description}`.toLowerCase();
            
            // Validate Color
            if (pColor) {
                let matchedColors = findAllMatches(textSource, colors);
                if (matchedColors.length === 0) {
                    status = 'INVALID';
                    reasons.push(`Color ${pColor} not found in source text for ${sourceProduct.sku}`);
                } else if (matchedColors.length > 1) {
                    if (status === 'SAFE_TO_APPROVE') status = 'NEEDS_REVIEW';
                    reasons.push(`Multiple colors found in ${sourceProduct.sku}: ${matchedColors.join(', ')}`);
                } else if (matchedColors[0] !== pColor) {
                    status = 'INVALID';
                    reasons.push(`Color conflict for ${sourceProduct.sku}: Profile=${pColor}, Source=${matchedColors[0]}`);
                }
            }

            // Validate Fabric
            if (pFabric) {
                let matchedFabrics = findAllMatches(textSource, fabrics);
                if (matchedFabrics.length === 0) {
                    status = 'INVALID';
                    reasons.push(`Fabric ${pFabric} not found in source text for ${sourceProduct.sku}`);
                } else if (matchedFabrics.length > 1) {
                    if (status === 'SAFE_TO_APPROVE') status = 'NEEDS_REVIEW';
                    reasons.push(`Multiple fabrics found in ${sourceProduct.sku}: ${matchedFabrics.join(', ')}`);
                } else if (matchedFabrics[0] !== pFabric) {
                    status = 'INVALID';
                    reasons.push(`Fabric conflict for ${sourceProduct.sku}: Profile=${pFabric}, Source=${matchedFabrics[0]}`);
                }
            }
            
            if (status === 'INVALID' && !results.conflicting_skus.includes(sourceProduct.sku)) {
                results.conflicting_skus.push(sourceProduct.sku);
            }
        }

        // De-duplicate reasons
        reasons = [...new Set(reasons)];

        results[status]++;
        if (status === 'SAFE_TO_APPROVE') results.products_safe += profile.product_ids.length;
        else if (status === 'NEEDS_REVIEW') results.products_needs_review += profile.product_ids.length;
        else results.products_invalid += profile.product_ids.length;

        results.profile_details.push({
            profile: pid,
            category: profile.category,
            products: profile.product_count,
            color: pColor,
            fabric: pFabric,
            pattern: pPattern,
            status: status,
            reasons: reasons.slice(0, 3).join(' | ') + (reasons.length > 3 ? '...' : '')
        });
    }

    fs.writeFileSync('meesho_profile_safety_results.json', JSON.stringify(results, null, 2));

    let md = `# Meesho Profile Safety Audit

## Summary
* Total Profiles: ${results.total_profiles}
* SAFE_TO_APPROVE: ${results.SAFE_TO_APPROVE}
* NEEDS_REVIEW: ${results.NEEDS_REVIEW}
* INVALID: ${results.INVALID}

## Product Coverage
* Products Safe: ${results.products_safe}
* Products Needs Review: ${results.products_needs_review}
* Products Invalid: ${results.products_invalid}

## Profile Details
| Profile | Category | Products | Color | Fabric | Status | Reason |
|---------|----------|----------|-------|--------|--------|--------|
`;

    for (let pd of results.profile_details) {
        md += `| ${pd.profile} | ${pd.category} | ${pd.products} | ${pd.color||'-'} | ${pd.fabric||'-'} | ${pd.status} | ${pd.reasons||'-'} |\n`;
    }

    fs.writeFileSync('meesho_profile_safety_audit.md', md);
    console.log("Validation complete.");
}

run().catch(console.error);
