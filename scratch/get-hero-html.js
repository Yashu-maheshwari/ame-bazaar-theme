const { chromium } = require('playwright');

async function run() {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();
  
  console.log("Navigating to https://amebazaar.in...");
  await page.goto('https://amebazaar.in/', { waitUntil: 'networkidle' });
  
  console.log("Locating element parent path...");
  const path = await page.evaluate(() => {
    const el = Array.from(document.querySelectorAll('a')).find(a => (a.getAttribute('href') || '').includes('boy-wear'));
    if (!el) return "Element NOT found!";
    
    // Build selector path
    const pathParts = [];
    let current = el;
    while (current && current.nodeType === Node.ELEMENT_NODE) {
      let selector = current.nodeName.toLowerCase();
      if (current.id) {
        selector += '#' + current.id;
        pathParts.unshift(selector);
        break;
      } else if (current.className) {
        selector += '.' + Array.from(current.classList).join('.');
      }
      pathParts.unshift(selector);
      current = current.parentNode;
    }
    return {
      path: pathParts.join(' > '),
      outerHtml: el.outerHTML,
      surroundHtml: el.parentElement ? el.parentElement.outerHTML.substring(0, 1000) : ''
    };
  });
  
  console.log("=== ELEMENT RESOLUTION ===");
  console.log(JSON.stringify(path, null, 2));
  
  await browser.close();
}

run().catch(err => {
  console.error(err);
});
