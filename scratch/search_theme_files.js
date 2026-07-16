const fs = require('fs');
const path = require('path');

function searchFiles(dir, query) {
    try {
        const files = fs.readdirSync(dir);
        for (const file of files) {
            const fullPath = path.join(dir, file).replace(/\\/g, '/');
            const stat = fs.statSync(fullPath);
            if (stat.isDirectory()) {
                if (!file.includes('node_modules') && !file.includes('.git')) {
                    searchFiles(fullPath, query);
                }
            } else if (file.endsWith('.php') || file.endsWith('.js')) {
                const content = fs.readFileSync(fullPath, 'utf8');
                if (content.toLowerCase().includes(query.toLowerCase())) {
                    console.log(`Found "${query}" in: ${fullPath}`);
                }
            }
        }
    } catch(e) {}
}

const themeDir = 'C:/Users/user/./.gemini/antigravity/scratch/ame-bazaar-git/wordpress/wp-content/themes/ame-bazaar';
searchFiles(themeDir, 'Homepage Media Manager');
searchFiles(themeDir, 'Media Manager');
searchFiles(themeDir, 'Banners Manager');
searchFiles(themeDir, 'Kids Wear');
searchFiles(themeDir, 'Boys Wear');
searchFiles(themeDir, 'Girls Wear');
searchFiles(themeDir, 'Uncategorized');
