const axios = require('axios');

async function checkComments() {
    try {
        const response = await axios.get('https://amebazaar.in/?nocache=' + Date.now());
        const html = response.data;
        
        console.log("DIAGNOSTIC COMMENTS AUDIT:");
        console.log("Component loaded comment exists:", html.includes('<!-- CATEGORIES COMPONENT LOADED -->'));
        
        // Find individual cards comments
        const regex = /<!-- TERM (\d+) -->\s*<!-- ATTACHMENT (\d+) -->\s*<!-- URL ([^\s]*) -->/gi;
        let match;
        while ((match = regex.exec(html)) !== null) {
            console.log(`Term ID: ${match[1]}, Attachment: ${match[2]}, URL: ${match[3]}`);
        }
    } catch (e) {
        console.error(e.message);
    }
}

checkComments();
