require('dotenv').config();
const https = require('https');
const fs = require('fs');

const wcOptions = {
    hostname: 'amebazaar.in',
    port: 443,
    auth: `${process.env.WC_CONSUMER_KEY}:${process.env.WC_CONSUMER_SECRET}`,
    headers: { 'User-Agent': 'Node.js' }
};

function fetchApi(path) {
    return new Promise((resolve, reject) => {
        let options = { ...wcOptions, method: 'GET', path };
        if (path.includes('wp/v2/media')) delete options.auth;
        const req = https.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                if (res.statusCode !== 200) return resolve({ data: [], totalPages: 0, total: 0 });
                const totalPages = parseInt(res.headers['x-wp-totalpages'] || '1', 10);
                const total = parseInt(res.headers['x-wp-total'] || '0', 10);
                try { resolve({ data: JSON.parse(data), totalPages, total }); } 
                catch(e) { resolve({ data: [], totalPages: 0, total: 0}); }
            });
        });
        req.on('error', reject);
        req.end();
    });
}

async function fetchAll(endpoint) {
    let first = await fetchApi(`${endpoint}&page=1`);
    let allData = [...first.data];
    let promises = [];
    for (let i = 2; i <= first.totalPages; i++) {
        promises.push(fetchApi(`${endpoint}&page=${i}`).then(res => res.data));
        if (promises.length >= 10 || i === first.totalPages) {
            let results = await Promise.all(promises);
            results.forEach(arr => allData = allData.concat(arr));
            promises = [];
        }
    }
    return allData;
}

function get_meesho_category_and_status(p) {
    let pName = (p.name || '').toLowerCase();
    let catNames = (p.categories || []).map(c => c.name.toLowerCase());
    let catString = catNames.join(' ');
    let str = pName + ' ' + catString;

    let isBoys = str.includes('boys') || str.includes('baba suit') || str.includes('boy');
    let isGirls = str.includes('girls') || str.includes('frock') || str.includes('girl');
    let isKids = isBoys || isGirls || str.includes('infant') || str.includes('baby');
    let isMen = str.includes('men') || (!isKids && !str.includes('women') && !str.includes('lady'));
    let isWomen = str.includes('women') || str.includes('lady') || str.includes('kurti') || str.includes('gown') || str.includes('lehenga');

    let cat = 'needs_review';
    let conf = 'None';

    // High Confidence Exact Matches
    if (str.includes('gown')) { cat = 'Women > Ethnic Wear > Gowns'; conf = 'High'; }
    else if (str.includes('sherwani')) { cat = 'Men > Ethnic Wear > Sherwanis'; conf = 'High'; }
    else if (str.includes('suit') && isMen && !str.includes('track') && !str.includes('baba')) { cat = 'Men > Western Wear > Suits'; conf = 'High'; }
    else if (str.includes('coat pant') || str.includes('coat-pant') || str.includes('blazer')) { cat = 'Men > Western Wear > Suits'; conf = 'High'; }
    else if (str.includes('baba suit') || str.includes('h/s set') || str.includes('f/s set') || str.includes('cloth set')) { cat = 'Kids > Boys Clothing > Clothing Sets'; conf = 'High'; }
    else if (str.includes('kurta pajama')) { cat = 'Men > Ethnic Wear > Kurta Sets'; conf = 'High'; }
    else if (str.includes('frock')) { cat = 'Kids > Girls Clothing > Frocks & Dresses'; conf = 'High'; }
    else if (str.includes('kurti') || str.includes('kurti set')) { cat = 'Women > Ethnic Wear > Kurtis'; conf = 'High'; }
    else if (str.includes('dress') && (isGirls || isWomen)) { cat = isGirls ? 'Kids > Girls Clothing > Frocks & Dresses' : 'Women > Western Wear > Dresses'; conf = 'High'; }
    
    // Deeper Classification for large generic categories (Boys/Girls/Men/Uncategorized)
    else if (str.includes('shirt') && !str.includes('tshirt') && !str.includes('t-shirt') && !str.includes('sweat')) {
        cat = isKids ? 'Kids > Boys Clothing > Shirts' : (isWomen ? 'Women > Western Wear > Shirts' : 'Men > Western Wear > Shirts');
        conf = 'High';
    }
    else if (str.includes('tshirt') || str.includes('t shirt') || str.includes('t-shirt')) {
        cat = isKids ? 'Kids > Boys Clothing > Tshirts' : (isWomen ? 'Women > Western Wear > Tshirts' : 'Men > Western Wear > Tshirts');
        conf = 'High';
    }
    else if (str.includes('jeans')) {
        cat = isKids ? (isGirls ? 'Kids > Girls Clothing > Jeans' : 'Kids > Boys Clothing > Jeans') : (isWomen ? 'Women > Western Wear > Jeans' : 'Men > Western Wear > Jeans');
        conf = 'High';
    }
    else if (str.includes('sweater') || str.includes('winter wear') || str.includes('sweatshirt') || str.includes('hood') || str.includes('cardigan') || str.includes('jacket')) {
        cat = isKids ? 'Kids > Boys & Girls Winter Wear' : (isWomen ? 'Women > Western Wear > Winter Wear' : 'Men > Western Wear > Winter Wear');
        conf = 'High';
    }
    else if (str.includes('undergarment') || str.includes('panty') || str.includes('bra') || str.includes('innerwear') || str.includes('slip') || str.includes('slips') || str.includes('supporter')) {
        cat = isWomen ? 'Women > Innerwear' : 'Men > Innerwear';
        conf = 'High';
    }
    else if (str.includes('nighty') || str.includes('p/j set') || str.includes('night suit') || str.includes('nightwear')) {
        cat = isWomen ? 'Women > Western Wear > Nightwear' : 'Men > Western Wear > Nightwear';
        conf = 'High';
    }
    else if (str.includes('towel')) {
        cat = 'Home & Kitchen > Bath > Towels';
        conf = 'High';
    }
    else if (str.includes('socks')) {
        cat = isKids ? 'Kids > Kids Accessories > Socks' : (isWomen ? 'Women > Western Wear > Socks' : 'Men > Innerwear > Socks'); // Men's socks are often under innerwear or accessories, let's use a generic safe one or High.
        conf = 'Medium';
    }
    else if (str.includes('top ') || str.includes('tops') || str.endsWith('top')) {
        cat = isGirls ? 'Kids > Girls Clothing > Tops & Tunics' : 'Women > Western Wear > Tops';
        conf = 'High';
    }
    else if (str.includes('capri') || str.includes('capry') || str.includes('plazo') || str.includes('lower') || str.includes('track') || str.includes('cargo') || str.includes('chinos') || str.includes('trouser') || str.includes('pant') || str.includes('jeggings') || str.includes('leggings')) {
        cat = isMen ? 'Men > Western Wear > Trousers & Pants' : 'Women > Western Wear > Trousers & Pants';
        conf = 'Medium'; // Pants can be ethnic or western, leaving as medium to be safe
    }

    if (conf !== 'High') return { cat: 'needs_review', conf };
    return { cat, conf };
}

async function run() {
    console.log('Starting Dry Run...');
    let [products, mediaArr] = await Promise.all([
        fetchAll('/wp-json/wc/v3/products?per_page=100&status=publish&_fields=id,name,sku,price,regular_price,images,categories,weight,slug'),
        fetchAll('/wp-json/wp/v2/media?per_page=100&_fields=id,title,source_url,slug')
    ]);

    let stats = {
        total: products.length,
        ready: 0,
        missing_image: 0,
        needs_review: 0,
        missing_sku: 0,
        missing_price: 0,
        images_recovered: 0
    };

    let categoriesStats = {};

    products.forEach(p => {
        let hasImage = p.images && p.images.length > 0;
        let imageRecovered = false;
        
        let pSku = p.sku ? p.sku : ('R-' + p.id); // Safely recovered SKU fallback
        let missingPrice = !p.price;
        
        if (!hasImage) {
            let matches = mediaArr.filter(m => {
                let mTitle = (m.title && m.title.rendered ? m.title.rendered.toLowerCase() : '');
                let mSlug = m.slug ? m.slug.toLowerCase() : '';
                let mUrl = m.source_url ? m.source_url.toLowerCase() : '';
                let skuLower = pSku.toLowerCase();
                let pSlug = p.slug ? p.slug.toLowerCase() : '';
                if (pSku && (mTitle.includes(skuLower) || mSlug.includes(skuLower) || mUrl.includes(skuLower))) return true;
                if (pSlug && (mTitle.includes(pSlug) || mSlug.includes(pSlug) || mUrl.includes(pSlug))) return true;
                return false;
            });
            if (matches.length > 0) {
                hasImage = true;
                imageRecovered = true;
                stats.images_recovered++;
            }
        }

        let { cat, conf } = get_meesho_category_and_status(p);
        
        let origCat = (p.categories && p.categories.length > 0) ? p.categories[0].name : 'Uncategorized';
        if (!categoriesStats[origCat]) categoriesStats[origCat] = { count: 0, mapped: cat, conf: conf, ready: 0, blocked: 0 };
        categoriesStats[origCat].count++;
        
        let blocked = false;
        
        if (!hasImage) { stats.missing_image++; blocked = true; }
        if (cat === 'needs_review') { stats.needs_review++; blocked = true; }
        if (!pSku) { stats.missing_sku++; blocked = true; } // Wont happen due to R- fallback
        if (missingPrice) { stats.missing_price++; blocked = true; }
        
        if (blocked) {
            categoriesStats[origCat].blocked++;
        } else {
            stats.ready++;
            categoriesStats[origCat].ready++;
        }
    });

    console.log('\\n--- DRY RUN RESULTS ---');
    console.log('TOTAL PRODUCTS:', stats.total);
    console.log('READY:', stats.ready);
    console.log('MISSING IMAGE:', stats.missing_image);
    console.log('NEEDS REVIEW (CATEGORY):', stats.needs_review);
    console.log('MISSING SKU:', stats.missing_sku);
    console.log('MISSING PRICE:', stats.missing_price);
    console.log('IMAGES RECOVERED:', stats.images_recovered);
    console.log('\nBEFORE: 25 ready');
    console.log('AFTER:', stats.ready, 'ready');

    console.log('\n--- CATEGORY BREAKDOWN ---');
    Object.keys(categoriesStats).sort((a,b) => categoriesStats[b].count - categoriesStats[a].count).forEach(k => {
        let s = categoriesStats[k];
        console.log(k + " | COUNT: " + s.count + " | MAPPED: " + s.mapped + " | CONFIDENCE: " + s.conf + " | READY: " + s.ready + " | BLOCKED: " + s.blocked);
    });
}

run();
