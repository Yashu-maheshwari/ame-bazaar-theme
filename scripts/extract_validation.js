const ExcelJS = require('exceljs');

async function run() {
    const wb = new ExcelJS.Workbook();
    await wb.xlsx.readFile('scripts/Tshirts-10000-EXTERNAL-MeeshoTemplate2PricesENROLMENT.xlsx');
    
    // Instructions or validation sheet
    const fillSheet = wb.getWorksheet('Tshirts-Fill this');
    console.log("=== COLUMNS & MANDATORY STATUS ===");
    for (let c = 1; c <= 40; c++) {
        let colName = fillSheet.getCell(2, c).value;
        let isMandatory = fillSheet.getCell(3, c).value; // Row 3 usually says Optional or Compulsory
        if (colName) {
            console.log(`${colName} | ${isMandatory}`);
        }
    }
    
    // Validation Sheet
    const valSheet = wb.getWorksheet('Validation Sheet') || wb.getWorksheet('Validation');
    if (valSheet) {
        console.log("\n=== VALIDATION RANGES ===");
        for(let c=1; c<=20; c++) {
            let hdr = valSheet.getCell(1, c).value;
            if (hdr) {
                let vals = [];
                for (let r=2; r<=15; r++) {
                    let v = valSheet.getCell(r, c).value;
                    if (v) vals.push(v);
                }
                console.log(`${hdr}: ${vals.join(', ')}${vals.length === 14 ? '...' : ''}`);
            }
        }
    }
}
run().catch(console.error);
