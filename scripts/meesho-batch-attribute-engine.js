const https = require('https');
const fs = require('fs');
const path = require('path');

const STATE_FILE = path.join(__dirname, 'meesho-profile-state.json');
const PREVIEW_DIR = path.join(__dirname, 'meesho_safe_previews');

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
const necks = ["Round", "V-Neck", "Polo", "Collar", "Henley", "Boat", "Square"];
const sleeves = ["Full", "Half", "Short", "Sleeveless", "3/4"];

function extract(text, list) {
    if(!text) return null;
    let t = text.toLowerCase();
    for(let val of list) {
        if(t.includes(val.toLowerCase())) return val;
    }
    return null;
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
    if (!fs.existsSync(PREVIEW_DIR)) fs.mkdirSync(PREVIEW_DIR);
    
    console.log("Fetching WooCommerce products for Batch Engine...");
    let products = [];
    let page = 1;
    while(true) {
        let batch = await fetchApi(`/wp-json/wc/v3/products?per_page=100&page=${page}`);
        if(batch.length === 0) break;
        products.push(...batch);
        page++;
        if (page > 20) break; // Limit to 2000 for this run to save time
    }
    console.log(`Fetched ${products.length} products.`);

    let state = {
        profiles: {},
        verified_measurements: {},
        audit_trail: []
    };

    let previews = {
        TSHIRT: [],
        FROCK: [],
        CLOTHING_SET: [],
        JEANS: [],
        UNKNOWN: []
    };
    
    let profileCounters = { TSHIRT: 1, FROCK: 1, CLOTHING_SET: 1, JEANS: 1, UNKNOWN: 1 };

    for (let p of products) {
        let textSource = `${p.name} ${p.short_description} ${p.description}`.toLowerCase();
        let cat = mapCategory(p.categories);
        
        let c = extract(textSource, colors);
        let f = extract(textSource, fabrics);
        let pat = extract(textSource, patterns);
        let n = extract(textSource, necks);
        let s = extract(textSource, sleeves);
        
        let evidenceLevel = (c || f || pat || n || s) ? 'B' : 'E';
        if (p.attributes && p.attributes.length > 0) evidenceLevel = 'A';
        
        // Grouping key based on found attributes
        let profileKeyRaw = `${cat}_${f||'NoFab'}_${c||'NoCol'}_${pat||'NoPat'}_${n||'NoNeck'}`;
        
        // Find existing profile or create new
        let existingProfileId = Object.keys(state.profiles).find(id => state.profiles[id].rawKey === profileKeyRaw);
        
        if (!existingProfileId) {
            existingProfileId = `${cat}_PROFILE_${String(profileCounters[cat]).padStart(3, '0')}`;
            profileCounters[cat]++;
            
            state.profiles[existingProfileId] = {
                profile_id: existingProfileId,
                rawKey: profileKeyRaw,
                category: cat,
                product_count: 0,
                product_ids: [],
                skus: [],
                status: 'AUTO-DETECTED', // Can be USER-APPROVED
                attributes: {
                    color: c ? { value: c, evidence_level: evidenceLevel, source: 'WooCommerce Desc/Title', confidence: 'High' } : { value: 'HUMAN_REQUIRED', evidence_level: 'E' },
                    fabric: f ? { value: f, evidence_level: evidenceLevel, source: 'WooCommerce Desc/Title', confidence: 'High' } : { value: 'HUMAN_REQUIRED', evidence_level: 'E' },
                    pattern: pat ? { value: pat, evidence_level: evidenceLevel, source: 'WooCommerce Desc/Title', confidence: 'High' } : { value: 'HUMAN_REQUIRED', evidence_level: 'E' },
                    neck: n ? { value: n, evidence_level: evidenceLevel, source: 'WooCommerce Desc/Title', confidence: 'High' } : { value: 'HUMAN_REQUIRED', evidence_level: 'E' },
                    sleeve: s ? { value: s, evidence_level: evidenceLevel, source: 'WooCommerce Desc/Title', confidence: 'High' } : { value: 'HUMAN_REQUIRED', evidence_level: 'E' }
                },
                measurements: 'HUMAN_REQUIRED'
            };
        }
        
        state.profiles[existingProfileId].product_count++;
        state.profiles[existingProfileId].product_ids.push(p.id);
        state.profiles[existingProfileId].skus.push(p.sku);
        
        // Add to Safe Preview
        let previewRow = {
            product_id: p.id,
            sku: p.sku,
            meesho_category: cat,
            assigned_profile: existingProfileId,
            color: c || 'HUMAN_REQUIRED',
            fabric: f || 'HUMAN_REQUIRED',
            pattern: pat || 'HUMAN_REQUIRED',
            neck: n || (cat==='TSHIRT' ? 'HUMAN_REQUIRED' : 'N/A'),
            sleeve: s || 'HUMAN_REQUIRED',
            measurement_status: 'HUMAN_REQUIRED',
            image_status: 'HUMAN_REQUIRED (Official Links Needed)',
            compliance_status: 'READY (From Profile)'
        };
        
        previews[cat].push(previewRow);
        
        // Audit Trail for auto-populated
        if (c || f || pat || n || s) {
            state.audit_trail.push({
                product_id: p.id,
                sku: p.sku,
                source: 'Engine_v1',
                evidence_level: evidenceLevel,
                mapped_value: { color: c, fabric: f, pattern: pat, neck: n, sleeve: s },
                timestamp: new Date().toISOString()
            });
        }
    }
    
    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
    
    // Write safe previews
    for (let c in previews) {
        fs.writeFileSync(path.join(PREVIEW_DIR, `${c}_preview.json`), JSON.stringify(previews[c], null, 2));
    }
    
    console.log("Batch engine complete.");
}

run().catch(console.error);
