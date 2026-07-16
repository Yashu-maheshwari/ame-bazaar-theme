const axios = require('axios');

async function checkComments() {
    try {
        const response = await axios.get('https://amebazaar.in/?nocache=' + Date.now());
        const html = response.data;
        const matches = html.match(/<!--[\s\S]*?-->/g);
        if (matches) {
            console.log(`Found ${matches.length} comments!`);
            console.log("Sample comments:", matches.slice(0, 10));
        } else {
            console.log("No comments found at all (Stripped by minification/caching!).");
        }
    } catch (e) {
        console.error(e.message);
    }
}

checkComments();
