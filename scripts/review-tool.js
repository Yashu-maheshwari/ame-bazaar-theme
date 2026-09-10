require('dotenv').config({path: __dirname + '/.env'});
const express = require('express');
const cors = require('cors');
const fs = require('fs');
const https = require('https');
const path = require('path');

const app = express();
app.use(cors());
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

const WC_URL = process.env.WC_URL || 'https://amebazaar.in';
const WC_KEY = process.env.WC_CONSUMER_KEY;
const WC_SECRET = process.env.WC_CONSUMER_SECRET;

const STATE_FILE = path.join(__dirname, 'meesho-review-state.json');
const TAXONOMY_FILE = path.join(__dirname, 'meesho-taxonomy.json');
const AUDIT_FILE = path.join(__dirname, '../scratch/meesho-audit-final-report.json');

// Initialize state file if not exists
if (!fs.existsSync(STATE_FILE)) {
    fs.writeFileSync(STATE_FILE, JSON.stringify({ products: {} }, null, 2));
}

let taxonomy = [];
if (fs.existsSync(TAXONOMY_FILE)) {
    taxonomy = JSON.parse(fs.readFileSync(TAXONOMY_FILE));
}

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

// Memory cache of queue
let queueIds = [];

// Load queue
function loadQueue() {
    if (!fs.existsSync(AUDIT_FILE)) return;
    const audit = JSON.parse(fs.readFileSync(AUDIT_FILE));
    
    // Prioritize 'blocked_only_category' (has image/sku/price) then 'blocked_multiple'
    let rawQueue = [...audit.lists.blocked_only_category, ...audit.lists.blocked_multiple];
    
    // Filter out already approved ones
    const state = JSON.parse(fs.readFileSync(STATE_FILE));
    queueIds = rawQueue.filter(id => !state.products[id]);
}

loadQueue();

app.get('/api/taxonomy', (req, res) => {
    res.json(taxonomy);
});

app.get('/api/queue-stats', (req, res) => {
    const state = JSON.parse(fs.readFileSync(STATE_FILE));
    const approvedCount = Object.keys(state.products).length;
    res.json({
        remaining: queueIds.length,
        approved: approvedCount
    });
});

app.get('/api/next', async (req, res) => {
    if (queueIds.length === 0) return res.json({ product: null });
    
    const id = queueIds[0];
    const product = await fetchWCProduct(id);
    
    if (!product) {
        queueIds.shift(); // remove invalid
        return res.json({ error: 'Product not found', id });
    }
    
    res.json({
        product: {
            id: product.id,
            name: product.name,
            sku: product.sku || ('R-' + product.id),
            categories: product.categories.map(c => c.name).join(', '),
            image: product.images && product.images.length > 0 ? product.images[0].src : null,
            description: product.short_description || product.description || ''
        }
    });
});

app.post('/api/approve', (req, res) => {
    const { id, sku, category, action } = req.body;
    
    if (action === 'approve') {
        const state = JSON.parse(fs.readFileSync(STATE_FILE));
        state.products[id] = {
            sku: sku,
            meesho_category: category,
            timestamp: new Date().toISOString(),
            status: 'approved'
        };
        fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
    }
    
    // Remove from queue regardless of approve or skip
    queueIds = queueIds.filter(q => q !== parseInt(id) && q !== String(id));
    
    res.json({ success: true });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Review tool running on http://localhost:${PORT}`);
});
