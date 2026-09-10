const sql = require('mssql');
const https = require('https');
require('dotenv').config({path: __dirname + '/.env'});

const WC_URL = process.env.WC_URL || 'https://amebazaar.in';
const WC_KEY = process.env.WC_CONSUMER_KEY;
const WC_SECRET = process.env.WC_CONSUMER_SECRET;

const dbConfig = {
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    server: process.env.DB_SERVER,
    database: process.env.DB_NAME,
    options: { encrypt: false, trustServerCertificate: true }
};

async function fetchWCProduct(id) {
    return new Promise((resolve, reject) => {
        https.get({
            hostname: new URL(WC_URL).hostname,
            path: `/wp-json/wc/v3/products/${id}`,
            auth: `${WC_KEY}:${WC_SECRET}`,
            headers: { 'User-Agent': 'Node.js' }
        }, res => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => resolve(JSON.parse(data)));
        }).on('error', reject);
    });
}

async function run() {
    console.log("=== WOOCOMMERCE PRODUCT INSPECTION ===");
    let wcProduct = await fetchWCProduct(14889);
    if (!wcProduct.id) {
        // Let's try searching for any T-shirt
        const ts = await new Promise((resolve, reject) => {
             https.get({
                hostname: new URL(WC_URL).hostname,
                path: `/wp-json/wc/v3/products?search=Tshirt&per_page=1`,
                auth: `${WC_KEY}:${WC_SECRET}`,
                headers: { 'User-Agent': 'Node.js' }
            }, res => {
                let data = '';
                res.on('data', chunk => data += chunk);
                res.on('end', () => resolve(JSON.parse(data)));
            }).on('error', reject);
        });
        if (ts.length > 0) wcProduct = ts[0];
    }
    
    console.log("Name:", wcProduct.name);
    console.log("Attributes:", JSON.stringify(wcProduct.attributes, null, 2));
    console.log("Meta Data:", JSON.stringify(wcProduct.meta_data, null, 2));
    console.log("Variations:", wcProduct.variations);
    
    let sku = wcProduct.sku;
    if (!sku) {
        // fallback
        sku = 'P-1234';
    }
    console.log("SKU:", sku);
    
    let raintechCode = sku; // Assuming SKU maps directly
    if (sku.startsWith('P-')) raintechCode = sku; // Adjust if needed

    console.log("\n=== RAINTECH DATABASE INSPECTION ===");
    try {
        await sql.connect(dbConfig);
        const result = await sql.query`SELECT TOP 5 * FROM Product WHERE ProductCode = ${raintechCode} OR ProductName LIKE '%tshirt%' OR ProductName LIKE '%cotton caneda%'`;
        if (result.recordset.length > 0) {
            console.log("Raintech Columns available:", Object.keys(result.recordset[0]));
            console.log("Sample Data:");
            const row = result.recordset[0];
            const interested = ['ProductCode', 'ProductName', 'MRP', 'RetailPrice', 'SalePrice', 'Color', 'Size', 'Fabric', 'Brand', 'Manufacturer', 'Description'];
            for (let k in row) {
                if (interested.some(i => k.toLowerCase().includes(i.toLowerCase()))) {
                    console.log(`  ${k}: ${row[k]}`);
                }
            }
        } else {
            console.log("No product found in Raintech for exact match, checking any top 1...");
            const top1 = await sql.query`SELECT TOP 1 * FROM Product`;
            if (top1.recordset.length > 0) {
                console.log("Raintech Columns available:", Object.keys(top1.recordset[0]));
            }
        }

        // Also check if there's any separate table for attributes
        const tables = await sql.query`SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'`;
        const tableNames = tables.recordset.map(t => t.TABLE_NAME);
        console.log("\nPossible Attribute/Variation Tables:", tableNames.filter(t => t.toLowerCase().includes('color') || t.toLowerCase().includes('size') || t.toLowerCase().includes('brand') || t.toLowerCase().includes('attr') || t.toLowerCase().includes('var') || t.toLowerCase().includes('mrp')));
        
    } catch (e) {
        console.error("SQL Error:", e);
    } finally {
        await sql.close();
    }
}
run();
