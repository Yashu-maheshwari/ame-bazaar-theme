require('dotenv').config({path: 'scripts/.env'});
const https = require('https');

if (!process.env.WP_USERNAME || !process.env.WP_APP_PASSWORD) {
    console.log('CREDENTIALS_MISSING');
    process.exit(1);
}

const auth = Buffer.from(process.env.WP_USERNAME + ':' + process.env.WP_APP_PASSWORD).toString('base64');
const options = {
    hostname: 'amebazaar.in',
    port: 443,
    path: '/wp-json/wp/v2/users/me',
    method: 'GET',
    headers: {
        'Authorization': 'Basic ' + auth,
        'User-Agent': 'Node.js'
    }
};

https.get(options, (res) => {
    let d = '';
    res.on('data', c => d += c);
    res.on('end', () => {
        if (res.statusCode === 200) {
            console.log('AUTH_SUCCESS');
        } else {
            console.log('AUTH_FAILED: HTTP ' + res.statusCode);
            console.log(d.substring(0, 150));
        }
    });
}).on('error', e => console.error('NETWORK_ERROR:', e.message));
