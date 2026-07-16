const axios = require('axios');

async function listComments() {
    try {
        const response = await axios.get('https://amebazaar.in/?nocache=' + Date.now());
        const html = response.data;
        const matches = html.match(/<!--[\s\S]*?-->/g);
        if (matches) {
            matches.forEach((c, idx) => {
                console.log(`${idx + 1}: ${c}`);
            });
        } else {
            console.log("No comments found.");
        }
    } catch (e) {
        console.error(e.message);
    }
}

listComments();
