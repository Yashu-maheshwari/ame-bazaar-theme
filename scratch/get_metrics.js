const axios = require('axios');
const fs = require('fs');

async function getFullMetrics() {
    try {
        const response = await axios.get('https://amebazaar.in/?rest_route=/ame/v1/import-raintech');
        fs.writeFileSync('C:\\Users\\user\\.gemini\\antigravity\\scratch\\metrics_response.json', JSON.stringify(response.data, null, 2), 'utf8');
        console.log("SUCCESS: saved metrics to metrics_response.json");
        console.log("Before Products Count:", response.data.before.products);
        console.log("After Products Count:", response.data.after.products);
        console.log("Before Categories Count:", response.data.before.categories);
        console.log("After Categories Count:", response.data.after.categories);
        console.log("New Categories Created:", response.data.after.new_categories_created);
        console.log("Summary:", response.data.summary);
        console.log("First Product details:", response.data.products[0]);
    } catch (e) {
        console.error("Error:", e.message);
    }
}

getFullMetrics();
