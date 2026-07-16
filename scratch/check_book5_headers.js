const XLSX = require('xlsx');

function readExcelHeaders(filePath) {
    console.log(`Reading: ${filePath}`);
    try {
        const workbook = XLSX.readFile(filePath);
        const firstSheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[firstSheetName];
        const data = XLSX.utils.sheet_to_json(worksheet);
        if (data.length > 0) {
            console.log("Headers:", Object.keys(data[0]));
            console.log("Row 1 sample:", data[0]);
            console.log("Total Rows:", data.length);
        } else {
            console.log("No data found.");
        }
    } catch (e) {
        console.error("Error reading file:", e.message);
    }
}

readExcelHeaders('C:\\Users\\user\\Downloads\\book5.xlsx');
