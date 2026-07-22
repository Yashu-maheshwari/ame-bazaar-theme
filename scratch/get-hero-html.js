const { chromium } = require('playwright');

(async () => {
	const browser = await chromium.launch({ headless: true });
	const page = await browser.newPage();
	
	page.on('pageerror', exception => {
		console.log(`Console Error: ${exception.message}`);
	});
	
	page.on('console', msg => {
		if (msg.type() === 'error') {
			console.log(`Console Log Error: ${msg.text()}`);
		}
	});

	console.log('Navigating to https://amebazaar.in...');
	try {
		await page.goto('https://amebazaar.in', { waitUntil: 'networkidle', timeout: 30000 });
		console.log('Navigation successful!');
		
		const heroExists = await page.locator('#ame-hero').count() > 0;
		if (heroExists) {
			const heroHTML = await page.locator('#ame-hero').innerHTML();
			console.log('--- Hero HTML ---');
			console.log(heroHTML);
			console.log('-----------------');
			
			const isVideoPresent = await page.locator('#ame-hero video').count() > 0;
			if (isVideoPresent) {
				const isPaused = await page.locator('#ame-hero video').evaluate(el => el.paused);
				const src = await page.locator('#ame-hero video').evaluate(el => el.src);
				const currentSrc = await page.locator('#ame-hero video').evaluate(el => el.currentSrc);
				console.log(`Video element found. Src: ${src}, CurrentSrc: ${currentSrc}`);
				console.log(`Is video paused? ${isPaused}`);
			} else {
				console.log('No video tag found inside #ame-hero.');
			}
		} else {
			console.log('Element #ame-hero not found on page.');
		}
	} catch (e) {
		console.error('Error during browser check:', e);
	}

	await browser.close();
})();
