const axios = require('axios');

async function findAttachments() {
    try {
        const response = await axios.get('https://amebazaar.in/wp-json/wp/v2/media?per_page=100');
        console.log("Media library files:");
        response.data.forEach(media => {
            console.log(`ID: ${media.id}, Slug: ${media.slug}, Title: ${media.title.rendered}, URL: ${media.source_url}`);
        });
    } catch (e) {
        console.error("Error:", e.message);
    }
}

findAttachments();
