const axios = require('axios');

async function searchAllMedia() {
    for (let page = 1; page <= 5; page++) {
        try {
            const response = await axios.get(`https://amebazaar.in/wp-json/wp/v2/media?per_page=100&page=${page}`);
            response.data.forEach(m => {
                const name = m.slug.toLowerCase();
                if (name.includes('shoe') || name.includes('foot') || name.includes('sandal') || name.includes('slip') || name.includes('boot')) {
                    console.log(`Page ${page} - ID: ${m.id}, Slug: ${m.slug}, URL: ${m.source_url}`);
                }
            });
        } catch (e) {
            break;
        }
    }
}

searchAllMedia();
