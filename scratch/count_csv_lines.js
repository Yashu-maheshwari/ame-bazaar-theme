const fs = require('fs');
const content = fs.readFileSync('C:\\Users\\user\\.gemini\\antigravity\\scratch\\ame-bazaar-git\\wordpress\\wp-content\\themes\\ame-bazaar\\inc\\raintech_products.csv', 'utf8');
const lines = content.split('\n');
console.log("Total Lines in CSV:", lines.length);
console.log("Line 1:", lines[0]);
console.log("Line 2:", lines[1]);
console.log("Line 3:", lines[2]);
console.log("Line 21:", lines[20]);
