const axios = require('axios');

async function countCategories() {
    try {
        const response = await axios.get('https://amebazaar.in/wp-json/wp/v2/product_cat?per_page=100&nocache=' + Date.now());
        console.log("WooCommerce Category Counts:");
        response.data.forEach(c => {
            if (c.count > 0) {
                console.log(`Slug: ${c.slug}, ID: ${c.id}, Parent: ${c.parent}, Count: ${c.count}`);
            }
        });
    } catch (e) {
        console.error(e.message);
    }
}

countCategories();
