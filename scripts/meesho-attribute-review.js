require('dotenv').config({path: __dirname + '/.env'});
const express = require('express');
const fs = require('fs');
const https = require('https');
const path = require('path');

const app = express();
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

const STATE_FILE = path.join(__dirname, 'meesho-attribute-state.json');
const CAT_STATE_FILE = path.join(__dirname, 'meesho-review-state.json');
const IMAGE_STATE_FILE = path.join(__dirname, 'recovery_state.json');
const AUDIT_FILE = path.join(__dirname, '..', 'scratch', 'meesho-audit-final-report.json');

const WC_URL = process.env.WC_URL || 'https://amebazaar.in';
const WC_KEY = process.env.WC_CONSUMER_KEY;
const WC_SECRET = process.env.WC_CONSUMER_SECRET;

// Ensure state exists
if (!fs.existsSync(STATE_FILE)) fs.writeFileSync(STATE_FILE, JSON.stringify({ attributes: {} }, null, 2));

function fetchWCProduct(id) {
    return new Promise((resolve, reject) => {
        https.get({
            hostname: new URL(WC_URL).hostname,
            path: `/wp-json/wc/v3/products/${id}`,
            auth: `${WC_KEY}:${WC_SECRET}`,
            headers: { 'User-Agent': 'Node.js' }
        }, res => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                if (res.statusCode === 200) resolve(JSON.parse(data));
                else resolve(null);
            });
        }).on('error', () => resolve(null));
    });
}

let queueIds = [];

function loadQueue() {
    if (!fs.existsSync(AUDIT_FILE)) return;
    const audit = JSON.parse(fs.readFileSync(AUDIT_FILE, 'utf-8'));
    
    // Priority 1: Ready products
    // Priority 2: Only blocked by category (meaning they have image & price, but need category review which might be done now)
    let rawQueue = [...(audit.lists.ready || []), ...(audit.lists.blocked_only_category || [])];
    
    // Filter out already approved in attributes state
    const state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf-8'));
    queueIds = rawQueue.filter(id => {
        return !state.attributes[id.toString()] || state.attributes[id.toString()].status !== 'approved';
    });
}

loadQueue();

function suggestAttributes(product) {
    let name = (product.name || '').toLowerCase();
    let sugg = {
        color: { value: '', source: 'UNKNOWN', conf: 'None' },
        fabric: { value: '', source: 'UNKNOWN', conf: 'None' },
        fit_shape: { value: '', source: 'UNKNOWN', conf: 'None' },
        neck: { value: '', source: 'UNKNOWN', conf: 'None' },
        pattern: { value: '', source: 'UNKNOWN', conf: 'None' },
        print_pattern_type: { value: '', source: 'UNKNOWN', conf: 'None' },
        sleeve_length: { value: '', source: 'UNKNOWN', conf: 'None' },
        chest_size: { value: '', source: 'UNKNOWN', conf: 'None' },
        length_size: { value: '', source: 'UNKNOWN', conf: 'None' },
        shoulder_size: { value: '', source: 'UNKNOWN', conf: 'None' },
    };

    const colors = ['red','blue','green','black','white','yellow','pink','purple','orange','grey','gray','brown','navy','maroon','olive','peach'];
    for (let c of colors) {
        if (name.includes(c)) {
            sugg.color = { value: c.charAt(0).toUpperCase() + c.slice(1), source: 'AI_SUGGESTION', conf: 'Medium' };
            break;
        }
    }

    if (name.includes('cotton')) sugg.fabric = { value: 'Cotton', source: 'AI_SUGGESTION', conf: 'Medium' };
    if (name.includes('silk')) sugg.fabric = { value: 'Silk', source: 'AI_SUGGESTION', conf: 'Medium' };
    
    if (name.includes('full sleeve') || name.includes('long sleeve')) sugg.sleeve_length = { value: 'Long Sleeves', source: 'AI_SUGGESTION', conf: 'High' };
    else if (name.includes('half sleeve') || name.includes('short sleeve')) sugg.sleeve_length = { value: 'Short Sleeves', source: 'AI_SUGGESTION', conf: 'High' };
    else if (name.includes('sleeveless')) sugg.sleeve_length = { value: 'Sleeveless', source: 'AI_SUGGESTION', conf: 'High' };

    if (name.includes('round neck')) sugg.neck = { value: 'Round Neck', source: 'AI_SUGGESTION', conf: 'High' };
    else if (name.includes('v neck') || name.includes('v-neck')) sugg.neck = { value: 'V-Neck', source: 'AI_SUGGESTION', conf: 'High' };
    else if (name.includes('collar')) sugg.neck = { value: 'Collared', source: 'AI_SUGGESTION', conf: 'High' };

    if (name.includes('printed') || name.includes('print')) sugg.pattern = { value: 'Printed', source: 'AI_SUGGESTION', conf: 'Medium' };
    else if (name.includes('solid') || name.includes('plain')) sugg.pattern = { value: 'Solid', source: 'AI_SUGGESTION', conf: 'Medium' };

    return sugg;
}

app.get('/api/queue-stats', (req, res) => {
    const state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf-8'));
    let approved = Object.keys(state.attributes).filter(k => state.attributes[k].status === 'approved').length;
    res.json({ remaining: queueIds.length, approved: approved });
});

app.get('/api/next-attribute', async (req, res) => {
    if (queueIds.length === 0) {
        // Fallback for testing UI if queue empty or failing
        const fallback = await new Promise(resolve => {
            https.get({
                hostname: new URL(WC_URL).hostname,
                path: `/wp-json/wc/v3/products?per_page=1`,
                auth: `${WC_KEY}:${WC_SECRET}`,
                headers: { 'User-Agent': 'Node.js' }
            }, r => {
                let d = '';
                r.on('data', chunk => d += chunk);
                r.on('end', () => resolve(JSON.parse(d)));
            }).on('error', () => resolve(null));
        });
        if (fallback && fallback.length > 0) {
            let fp = fallback[0];
            queueIds.push(fp.id);
        } else {
            return res.json({ error: false, product: null });
        }
    }
    
    let id = queueIds[0];
    let product = await fetchWCProduct(id);
    
    if (!product || product.code === 'internal_server_error' || !product.id) {
        // If single product fetch fails (500), try to fetch from list API
        const listFallback = await new Promise(resolve => {
            https.get({
                hostname: new URL(WC_URL).hostname,
                path: `/wp-json/wc/v3/products?include=${id}`,
                auth: `${WC_KEY}:${WC_SECRET}`,
                headers: { 'User-Agent': 'Node.js' }
            }, r => {
                let d = '';
                r.on('data', chunk => d += chunk);
                r.on('end', () => resolve(JSON.parse(d)));
            }).on('error', () => resolve(null));
        });
        
        if (listFallback && listFallback.length > 0) {
            product = listFallback[0];
        } else {
            queueIds.shift();
            return res.json({ error: 'Product not found', product: null });
        }
    }

    // Determine meesho category
    let mCat = 'N/A';
    if (fs.existsSync(CAT_STATE_FILE)) {
        let cs = JSON.parse(fs.readFileSync(CAT_STATE_FILE, 'utf-8'));
        if (cs.products && cs.products[id] && cs.products[id].status === 'approved') {
            mCat = cs.products[id].meesho_category;
        }
    }

    // Determine image
    let imgUrl = product.images && product.images.length > 0 ? product.images[0].src : null;
    if (fs.existsSync(IMAGE_STATE_FILE)) {
        let is = JSON.parse(fs.readFileSync(IMAGE_STATE_FILE, 'utf-8'));
        if (is.products && is.products[id] && is.products[id].status === 'recovered') {
            imgUrl = is.products[id].wp_url;
        }
    }

    let p = {
        id: product.id,
        name: product.name,
        sku: product.sku || ('R-' + product.id),
        categories: product.categories.map(c => c.name).join(', '),
        meesho_category: mCat,
        display_image: imgUrl
    };
    
    let suggestions = suggestAttributes(p);

    res.json({ error: false, product: p, suggestions: suggestions });
});

app.post('/api/approve-attribute', (req, res) => {
    let data = req.body;
    let idStr = data.product_id.toString();
    
    const state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf-8'));

    if (data.action === 'skip') {
        state.attributes[idStr] = { status: 'skipped', timestamp: new Date().toISOString() };
    } else {
        state.attributes[idStr] = {
            sku: data.sku,
            meesho_category: data.meesho_category,
            color: data.color,
            fabric: data.fabric,
            fit_shape: data.fit_shape,
            neck: data.neck,
            pattern: data.pattern,
            print_pattern_type: data.print_pattern_type,
            sleeve_length: data.sleeve_length,
            chest_size: data.chest_size,
            length_size: data.length_size,
            shoulder_size: data.shoulder_size,
            status: 'approved',
            timestamp: new Date().toISOString()
        };
    }
    
    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
    
    queueIds = queueIds.filter(q => q !== parseInt(idStr) && q !== idStr);
    
    res.json({ success: true });
});

app.get('/api/batch-profiles', (req, res) => {
    const PROFILE_STATE = path.join(__dirname, 'meesho-profile-state.json');
    if (!fs.existsSync(PROFILE_STATE)) return res.json({ profiles: {} });
    const state = JSON.parse(fs.readFileSync(PROFILE_STATE, 'utf-8'));
    res.json({ profiles: state.profiles, measurements: state.verified_measurements });
});

app.post('/api/batch-profiles/approve', (req, res) => {
    const PROFILE_STATE = path.join(__dirname, 'meesho-profile-state.json');
    let data = req.body;
    const state = JSON.parse(fs.readFileSync(PROFILE_STATE, 'utf-8'));
    
    let profile = state.profiles[data.profile_id];
    if (profile) {
        profile.status = 'USER-APPROVED';
        profile.approved_by = 'user';
        profile.approval_timestamp = new Date().toISOString();
        
        // Update attributes to User-Approved
        for (let k in data.attributes) {
            profile.attributes[k].value = data.attributes[k];
            profile.attributes[k].source = 'User Approval';
        }
        
        fs.writeFileSync(PROFILE_STATE, JSON.stringify(state, null, 2));
        res.json({ success: true });
    } else {
        res.json({ success: false, error: 'Profile not found' });
    }
});

const sizeChartManager = require('./meesho-size-chart-manager');
app.use('/api/size-charts', sizeChartManager);

const PORT = 3001;
app.listen(PORT, () => {
    console.log(`Attribute Review tool running on http://localhost:${PORT}`);
});
