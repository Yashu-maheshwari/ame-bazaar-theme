const http = require('http');

const payload = {
    rawText: `https://upload.meeshosupplyassets.com/cataloging/1789136583704/P-0007_1-Copy.jpghttps://upload.meeshosupplyassets.com/cataloging/1789136583940/P-0008_1-Copy.jpghttps://upload.meeshosupplyassets.com/cataloging/1789136583611/P-0009_1-Copy.jpghttps://upload.meeshosupplyassets.com/cataloging/1789136583616/P-0010_1-Copy.jpghttps://google.com/test.jpghttps://upload.meeshosupplyassets.com/cataloging/123/UNKNOWN_SKU_1-Copy.jpghttps://upload.meeshosupplyassets.com/cataloging/123/P-0007_2-Copy.jpghttps://upload.meeshosupplyassets.com/cataloging/123/P-0007_1-Copy.jpg`,
    dryRun: true
};

const data = JSON.stringify(payload);

const options = {
    hostname: 'localhost',
    port: 3002,
    path: '/api/capture-links',
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Content-Length': data.length
    }
};

const req = http.request(options, (res) => {
    let responseData = '';
    res.on('data', (chunk) => {
        responseData += chunk;
    });
    res.on('end', () => {
        console.log(JSON.parse(responseData));
    });
});

req.on('error', (e) => {
    console.error(`Problem with request: ${e.message}`);
});

req.write(data);
req.end();
