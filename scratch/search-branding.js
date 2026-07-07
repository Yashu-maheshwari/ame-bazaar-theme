const fs = require('fs');
const media = JSON.parse(fs.readFileSync('C:/Users/user/.gemini/antigravity/brain/217da65a-e7e1-429e-afe7-5175d47a2cc6/scratch/media-library.json', 'utf8'));

console.log('--- ALL ATTACHMENTS WITH LOGO OR ICON OR BRAND ---');
let found = 0;
media.forEach(item => {
  const name = item.name.toLowerCase();
  const title = item.title.toLowerCase();
  if (name.includes('logo') || title.includes('logo') || name.includes('icon') || title.includes('icon') || name.includes('brand') || title.includes('brand')) {
    found++;
    console.log('ID: ' + item.id + ' | Title: ' + item.title + ' | Slug: ' + item.name + ' | URL: ' + item.url);
  }
});
if (found === 0) {
  console.log('None found!');
}
