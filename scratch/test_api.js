const axios = require('axios');

async function testApi() {
    try {
        const response = await axios.get('https://amebazaar.in/?rest_route=/ame/v1/import-raintech');
        console.log("Success Response Data:", response.data);
    } catch (e) {
        if (e.response) {
            console.log("Error Status:", e.response.status);
            console.log("Error Response Data:", e.response.data);
        } else {
            console.log("Error:", e.message);
        }
    }
}

testApi();
