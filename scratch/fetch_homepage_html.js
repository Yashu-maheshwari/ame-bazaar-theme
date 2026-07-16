const axios = require('axios');

async function checkHomepage() {
    try {
        const response = await axios.get('https://amebazaar.in/?nocache=' + Date.now());
        const html = response.data;
        
        console.log("HOMEPAGE CATEGORY IMAGES AUDIT:");
        
        const regex = /<a[^>]*data-category-slug=\"([^\"]+)\"[^>]*>[\s\S]*?<img[^>]*src=\"([^\"]+)\"[^>]*>/gi;
        let match;
        while ((match = regex.exec(html)) !== null) {
            console.log(`Slug: ${match[1]}, Image Src: ${match[2]}`);
        }
    } catch (e) {
        console.error("Error:", e.message);
    }
}

checkHomepage();
