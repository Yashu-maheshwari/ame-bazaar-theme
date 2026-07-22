const https = require('https');

https.get('https://amebazaar.in/wp-json/ame-bazaar/v1/media-audit', (res) => {
	let data = '';
	res.on('data', (chunk) => {
		data += chunk;
	});
	res.on('end', () => {
		try {
			console.log(JSON.stringify(JSON.parse(data), null, 2));
		} catch (e) {
			console.log('Raw response:', data);
		}
	});
}).on('error', (err) => {
	console.error('Fetch error:', err.message);
});
