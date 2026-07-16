const axios = require('axios');

async function getCategories() {
    try {
        const response = await axios.get('https://amebazaar.in/wp-json/wp/v2/product_cat?per_page=100');
        console.log("Categories data:");
        response.data.forEach(cat => {
            console.log(`ID: ${cat.id}, Slug: ${cat.slug}, Name: ${cat.name}, Meta:`, cat.meta);
        });
    } catch (e) {
        console.error("Error:", e.message);
    }
}

getCategories();
