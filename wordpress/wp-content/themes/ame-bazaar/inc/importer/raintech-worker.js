const fs = require('fs');
const path = require('path');
const xlsx = require('xlsx');

// Retrieve arguments
const inputFile = process.argv[2];
const outputFile = process.argv[3];

if (!inputFile || !outputFile) {
    console.error("Usage: node raintech-worker.js <input_file> <output_file>");
    process.exit(1);
}

try {
    console.log(`Reading file: ${inputFile}`);
    
    // Parse the file (xlsx handles CSV, XLS, XLSX, TXT internally)
    const workbook = xlsx.readFile(inputFile);
    const sheetName = workbook.SheetNames[0];
    const rawData = xlsx.utils.sheet_to_json(workbook.Sheets[sheetName]);
    
    console.log(`Parsed ${rawData.length} rows.`);

    const mappedProducts = [];
    let rowsProcessed = 0;
    
    // Process each row (AI Mapping Simulation)
    for (const row of rawData) {
        rowsProcessed++;
        
        // Extract basic fields based on Raintech variations
        const rawTitle = row['Product Name'] || row['Item Name'] || row['Title'] || '';
        const productCode = row['Product Code'] || row['Item Code'] || row['SKU'] || '';
        const barcode = row['Barcode'] || '';
        const mrp = row['MRP'] || row['Regular Price'] || 0;
        const price = row['Retail Price'] || row['Retail Sale Price'] || row['Selling Price'] || row['Sale Price'] || 0;
        const rawDept = row['Category'] || row['Department'] || '';
        const rawCat = row['Sub Category / Brand'] || row['Subcategory'] || '';
        
        if (!rawTitle) continue;

        // "AI Mapping": Derive detailed attributes
        const cleanTitle = rawTitle.replace(/[^a-zA-Z0-9\s]/g, '').trim();
        const slug = cleanTitle.toLowerCase().replace(/\s+/g, '-');
        
        // Department Mapping
        let department = 'uncategorized';
        const dLow = rawDept.toLowerCase() + ' ' + rawTitle.toLowerCase();
        if (dLow.includes('men')) department = 'mens-wear';
        else if (dLow.includes('women') || dLow.includes('lady')) department = 'womens-wear';
        else if (dLow.includes('kid') || dLow.includes('boy') || dLow.includes('girl')) department = 'kids-wear';
        else if (dLow.includes('shoe') || dLow.includes('foot')) department = 'footwear';
        else if (dLow.includes('accessory') || dLow.includes('belt') || dLow.includes('wallet')) department = 'accessories';

        const mappedProduct = {
            raw_title: rawTitle,
            clean_title: cleanTitle,
            sku: productCode || `SKU-${Date.now()}-${rowsProcessed}`,
            barcode: barcode,
            mrp: parseFloat(mrp),
            price: parseFloat(price),
            department: department,
            category: rawCat || 'General',
            seo_title: `${cleanTitle} | AME Store - Buy Online`,
            seo_desc: `Purchase ${cleanTitle} at AME Store. High quality, premium selection.`,
            short_desc: `Premium quality ${cleanTitle} for all occasions.`,
            long_desc: `<h2>${cleanTitle}</h2><p>This premium item ensures maximum comfort and style. It falls under our exclusive ${department} collection.</p>`,
            ai_summary: `AI analyzed: Validated product ${cleanTitle} under ${department}.`
        };
        
        mappedProducts.push(mappedProduct);
    }
    
    // Write output mapping to JSON
    const outputData = {
        metrics: {
            rowsProcessed: rowsProcessed,
            totalMapped: mappedProducts.length
        },
        products: mappedProducts
    };
    
    fs.writeFileSync(outputFile, JSON.stringify(outputData, null, 2));
    console.log(`Successfully mapped ${mappedProducts.length} products. Output written to ${outputFile}`);
    process.exit(0);

} catch (error) {
    console.error("Error processing file:", error);
    process.exit(1);
}
