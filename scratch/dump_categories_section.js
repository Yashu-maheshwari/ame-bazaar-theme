const axios = require('axios');

async function dumpSection() {
    try {
        const response = await axios.get('https://amebazaar.in/?nocache=' + Date.now());
        const html = response.data;
        const start = html.indexOf('<section class="ame-categories-section"');
        const end = html.indexOf('</section>', start);
        if (start !== -1 && end !== -1) {
            console.log(html.substring(start, end + 10));
        } else {
            console.log("Section not found.");
        }
    } catch (e) {
        console.error(e.message);
    }
}

dumpSection();
