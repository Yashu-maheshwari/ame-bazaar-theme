const XLSX = require('xlsx');
const fs = require('fs');
const path = require('path');

function convertToCsv() {
    const inputPath = 'C:\\Users\\user\\Downloads\\book5.xlsx';
    const outputPath = 'C:\\Users\\user\\.gemini\\antigravity\\scratch\\ame-bazaar-git\\wordpress\\wp-content\\themes\\ame-bazaar\\inc\\raintech_products.csv';

    console.log(`Converting ${inputPath} to CSV...`);
    try {
        const workbook = XLSX.readFile(inputPath);
        const firstSheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[firstSheetName];
        
        // Convert to CSV string
        const csvContent = XLSX.utils.sheet_to_csv(worksheet);
        
        // Ensure directory exists
        fs.mkdirSync(path.dirname(outputPath), { recursive: true });
        
        // Write file
        fs.writeFileSync(outputPath, csvContent, 'utf8');
        console.log(`Successfully saved CSV to ${outputPath}`);
        console.log(`Size: ${fs.statSync(outputPath).size} bytes`);
    } catch (e) {
        console.error("Error during conversion:", e.message);
    }
}

convertToCsv();
