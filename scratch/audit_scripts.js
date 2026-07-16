const axios = require('axios');

async function auditScripts() {
    try {
        const response = await axios.get('https://amebazaar.in/?nocache=' + Date.now());
        const html = response.data;
        
        console.log("ENQUEUED SCRIPTS AUDIT:");
        
        // Find script tags
        const regex = /<script[^>]*src=\"([^\"]+)\"[^>]*>/gi;
        let match;
        while ((match = regex.exec(html)) !== null) {
            console.log(match[1]);
        }
        
        // Look for inline lazyload configurations
        console.log("\nSEARCH FOR LAZYLOAD INLINE SCRIPT SECTIONS:");
        const inlineRegex = /<script[^>]*>([\s\S]*?lazy[\s\S]*?)<\/script>/gi;
        while ((match = inlineRegex.exec(html)) !== null) {
            console.log("Found inline lazy script configuration segment (First 300 chars):");
            console.log(match[1].trim().substring(0, 300));
            console.log("------------------------");
        }
    } catch (e) {
        console.error(e.message);
    }
}

auditScripts();
