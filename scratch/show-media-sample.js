const fs = require('fs');
const media = JSON.parse(fs.readFileSync('C:/Users/user/.gemini/antigravity/brain/217da65a-e7e1-429e-afe7-5175d47a2cc6/scratch/media-library.json', 'utf8'));

console.log('--- FIRST 50 ATTACHMENTS ---');
media.slice(0, 50).forEach(item => {
  console.log('ID: ' + item.id + ' | Title: ' + item.title + ' | Slug: ' + item.name + ' | URL: ' + item.url);
});
