const axios = require('axios');

async function checkTerm() {
    try {
        const response = await axios.get('https://amebazaar.in/wp-json/wp/v2/product_cat?slug=kids-wear&nocache=' + Date.now());
        console.log("Term details:", JSON.stringify(response.data, null, 2));
    } catch (e) {
        console.error(e.message);
    }
}

checkTerm();
