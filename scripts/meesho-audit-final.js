require('dotenv').config({path: 'scripts/.env'});
const https = require('https');
const fs = require('fs');

const WC_URL = process.env.WC_URL || 'https://amebazaar.in';
const WC_KEY = process.env.WC_CONSUMER_KEY;
const WC_SECRET = process.env.WC_CONSUMER_SECRET;

function fetchAll(apiPath) {
    return new Promise(async (resolve, reject) => {
        let results = [];
        let page = 1;
        while (true) {
            let res = await new Promise((r) => {
                const url = new URL(apiPath + (apiPath.includes('?') ? '&' : '?') + 'page=' + page, WC_URL);
                const options = {
                    hostname: url.hostname,
                    port: 443,
                    path: url.pathname + url.search,
                    method: 'GET',
                    auth: `${WC_KEY}:${WC_SECRET}`,
                    headers: { 'User-Agent': 'Node.js' }
                };
                https.get(options, res => {
                    let data = '';
                    res.on('data', chunk => data += chunk);
                    res.on('end', () => r({ status: res.statusCode, data: JSON.parse(data) }));
                }).on('error', e => r({ status: 500, error: e }));
            });
            if (res.status !== 200 || !Array.isArray(res.data) || res.data.length === 0) break;
            results = results.concat(res.data);
            page++;
        }
        resolve(results);
    });
}

function get_meesho_category_and_status(product) {
    let categories = product.categories || [];
    let catNames = categories.map(c => c.name.toLowerCase()).join(' ');
    let name = (product.name || '').toLowerCase();
    let str = name + ' ' + catNames;

    let isKids = str.includes('kids') || str.includes('baba') || str.includes('baby') || str.includes('frock') || str.includes('boy') || str.includes('girl') || str.includes('infant');
    let isGirls = str.includes('girl') || str.includes('frock');
    let isMen = str.includes('men') || str.includes('gents') || str.includes('boy') || (!isKids && !str.includes('women') && !str.includes('lady'));
    let isWomen = str.includes('women') || str.includes('lady') || str.includes('ladies') || str.includes('female') || str.includes('kurti') || str.includes('gown') || str.includes('lehenga');

    let cat = 'needs_review';
    let conf = 'None';

    // 1. Women / Ethnic / Sets
    if (str.includes('gown')) { cat = 'Women > Ethnic Wear > Gowns'; conf = 'High'; }
    else if (str.includes('sherwani')) { cat = 'Men > Ethnic Wear > Sherwanis'; conf = 'High'; }
    else if (str.includes('suit') && isMen && !str.includes('track') && !str.includes('baba') && !str.includes('night')) { cat = 'Men > Western Wear > Suits'; conf = 'High'; }
    else if (str.includes('coat pant') || str.includes('coat-pant') || str.includes('blazer')) { cat = 'Men > Western Wear > Suits'; conf = 'High'; }
    else if (str.includes('baba suit') || str.includes('h/s set') || str.includes('f/s set') || str.includes('cloth set')) { cat = 'Kids > Boys Clothing > Clothing Sets'; conf = 'High'; }
    else if (str.includes('kurta pajama') || str.includes('k/p')) { cat = 'Men > Ethnic Wear > Kurta Sets'; conf = 'High'; }
    else if (str.includes('frock')) { cat = 'Kids > Girls Clothing > Frocks & Dresses'; conf = 'High'; }
    else if (str.includes('kurti') || str.includes('kurti set')) { cat = 'Women > Ethnic Wear > Kurtis'; conf = 'High'; }
    else if (str.includes('dress') && (isGirls || isWomen)) { cat = isGirls ? 'Kids > Girls Clothing > Frocks & Dresses' : 'Women > Western Wear > Dresses'; conf = 'High'; }
    else if (str.includes('waist coat') || str.includes('koati') || str.includes('koti')) { cat = 'Men > Ethnic Wear > Ethnic Jackets'; conf = 'High'; }
    else if (str.includes('plazo') || str.includes('sharara') || str.includes('divider')) { cat = 'Women > Ethnic Wear > Palazzos'; conf = 'High'; }
    else if (str.includes('blouse')) { cat = 'Women > Ethnic Wear > Blouses'; conf = 'High'; }
    else if (str.includes('co ord set') || str.includes('co-ord set') || str.includes('cord set')) { cat = 'Women > Western Wear > Co-ords'; conf = 'High'; }
    
    // 2. Western Wear Tops
    else if (str.includes('shirt') && !str.includes('tshirt') && !str.includes('t-shirt') && !str.includes('sweat')) {
        cat = isKids ? 'Kids > Boys Clothing > Shirts' : (isWomen ? 'Women > Western Wear > Shirts' : 'Men > Western Wear > Shirts');
        conf = 'High';
    }
    else if (str.includes('tshirt') || str.includes('t shirt') || str.includes('t-shirt')) {
        cat = isKids ? 'Kids > Boys Clothing > Tshirts' : (isWomen ? 'Women > Western Wear > Tshirts' : 'Men > Western Wear > Tshirts');
        conf = 'High';
    }
    else if (str.includes('top ') || str.includes('tops') || str.endsWith('top') || str.includes('middy')) {
        cat = isGirls ? 'Kids > Girls Clothing > Tops & Tunics' : 'Women > Western Wear > Tops';
        conf = 'High';
    }

    // 3. Western Wear Bottoms
    else if (str.includes('jeans') || str.includes('denim')) {
        cat = isKids ? (isGirls ? 'Kids > Girls Clothing > Jeans' : 'Kids > Boys Clothing > Jeans') : (isWomen ? 'Women > Western Wear > Jeans' : 'Men > Western Wear > Jeans');
        conf = 'High';
    }
    else if (str.includes('legging') || str.includes('jegging') || str.includes('jagging') || str.includes('tighty')) {
        cat = 'Women > Western Wear > Jeggings';
        conf = 'High';
    }
    else if (str.includes('lower') || str.includes('track') || str.includes('cargo') || str.includes('chinos') || str.includes('trouser') || str.includes('pant') || str.includes('pents')) {
        cat = isKids ? (isGirls ? 'Kids > Girls Clothing > Trackpants & Trousers' : 'Kids > Boys Clothing > Trackpants & Trousers') : (isWomen ? 'Women > Western Wear > Trousers & Pants' : 'Men > Western Wear > Trousers & Pants');
        conf = 'High';
    }
    else if (str.includes('capri') || str.includes('capry') || str.includes('bermuda') || str.includes('nikar') || str.includes('nikker') || str.includes('neckar') || str.includes('shorts') || str.includes('shorties')) {
        cat = isKids ? (isGirls ? 'Kids > Girls Clothing > Shorts' : 'Kids > Boys Clothing > Shorts') : (isWomen ? 'Women > Western Wear > Shorts' : 'Men > Western Wear > Shorts');
        conf = 'High';
    }

    // 4. Winterwear & Nightwear
    else if (str.includes('sweater') || str.includes('winter wear') || str.includes('sweatshirt') || str.includes('hood') || str.includes('cardigan') || str.includes('jacket')) {
        cat = isKids ? 'Kids > Boys & Girls Winter Wear' : (isWomen ? 'Women > Western Wear > Winter Wear' : 'Men > Western Wear > Winter Wear');
        conf = 'High';
    }
    else if (str.includes('nighty') || str.includes('p/j set') || str.includes('night suit') || str.includes('nightwear')) {
        cat = isWomen ? 'Women > Western Wear > Nightwear' : 'Men > Western Wear > Nightwear';
        conf = 'High';
    }

    // 5. Innerwear
    else if (str.includes('bra ') || str.includes('panty') || str.includes('slips') || str.includes('skivi')) {
        cat = 'Women > Innerwear';
        conf = 'High';
    }
    else if (str.includes('undergarment') || str.includes('innerwear') || str.includes('trunk') || str.includes('vest') || str.includes('supporter')) {
        cat = 'Men > Innerwear';
        conf = 'High';
    }

    // 6. Accessories & Home
    else if (str.includes('towel') || str.includes('gamcha') || str.includes('hanky') || str.includes('hankey') || str.includes('bedsheet') || str.includes('dry sheet') || str.includes('baby bed') || str.includes('mosquito net') || str.includes('pillow')) {
        cat = str.includes('towel') || str.includes('gamcha') ? 'Home & Kitchen > Bath > Towels' : 'Home & Kitchen > Bedding > Bedsheets';
        conf = 'High';
    }
    else if (str.includes('socks')) {
        cat = 'Men > Innerwear > Socks';
        conf = 'High';
    }
    else if (str.includes('belt')) {
        cat = 'Men > Men Accessories > Belts';
        conf = 'High';
    }
    else if (str.includes('gift set') || str.includes('rattle') || str.includes('diaper') || str.includes('bib') || str.includes('cap set')) {
        cat = 'Kids > Infant Clothing > Clothing Sets';
        conf = 'High';
    }
    else if (str.includes('rain coat') || str.includes('raincoat')) {
        cat = 'Men > Western Wear > Raincoats';
        conf = 'High';
    }
    else if (str.includes('bottle')) {
        cat = 'Home & Kitchen > Kitchen Storage > Water Bottles';
        conf = 'High';
    }

    if (conf !== 'High') return { cat: 'needs_review', conf };
    return { cat, conf };
}

function get_meesho_weight(product) {
    let categories = product.categories || [];
    let catNames = categories.map(c => c.name.toLowerCase()).join(' ');
    let name = (product.name || '').toLowerCase();
    let str = name + ' ' + catNames;

    if (str.includes('gown') || str.includes('coat pant') || str.includes('coat-pant') || str.includes('suit') || str.includes('sherwani') || str.includes('blazer') || str.includes('lehenga')) {
        return 1500;
    }
    return 500;
}

async function run() {
    console.log('Starting Complete Meesho Readiness Audit...');
    const products = await fetchAll('/wp-json/wc/v3/products?per_page=100&status=publish&_fields=id,name,sku,price,images,categories');

    let report = {
        total: products.length,
        ready: 0,
        blocked: 0,
        blocked_reasons: {
            missing_image: 0,
            missing_price: 0,
            missing_sku: 0,
            needs_review: 0
        },
        lists: {
            ready: [],
            blocked_only_category: [],
            blocked_only_image: [],
            blocked_multiple: []
        },
        categories: {},
        top_unmapped: {}
    };

    products.forEach(p => {
        let hasImage = p.images && p.images.length > 0;
        let pSku = p.sku ? p.sku : ('R-' + p.id);
        let hasPrice = p.price !== undefined && p.price !== '';
        
        let { cat, conf } = get_meesho_category_and_status(p);
        let weight = get_meesho_weight(p);

        let origCat = (p.categories && p.categories.length > 0) ? p.categories[0].name : 'Uncategorized';
        if (!report.categories[origCat]) report.categories[origCat] = { count: 0, ready: 0 };
        report.categories[origCat].count++;

        let isBlocked = false;
        let reasons = [];

        if (!hasImage) reasons.push('missing_image');
        if (!hasPrice) reasons.push('missing_price');
        if (!pSku) reasons.push('missing_sku'); // shouldn't happen
        if (cat === 'needs_review') reasons.push('needs_review');

        if (reasons.length === 0) {
            report.ready++;
            report.lists.ready.push(p.id);
            report.categories[origCat].ready++;
        } else {
            report.blocked++;
            reasons.forEach(r => report.blocked_reasons[r]++);

            if (reasons.length === 1 && reasons[0] === 'needs_review') {
                report.lists.blocked_only_category.push(p.id);
            } else if (reasons.length === 1 && reasons[0] === 'missing_image') {
                report.lists.blocked_only_image.push(p.id);
            } else if (reasons.length > 1) {
                report.lists.blocked_multiple.push(p.id);
            }

            if (reasons.includes('needs_review')) {
                if (!report.top_unmapped[origCat]) report.top_unmapped[origCat] = 0;
                report.top_unmapped[origCat]++;
            }
        }
    });

    if (!fs.existsSync('scratch')) fs.mkdirSync('scratch');
    fs.writeFileSync('scratch/meesho-audit-final-report.json', JSON.stringify(report, null, 2));
    console.log('Audit completed. Report saved to scratch/meesho-audit-final-report.json');
}

run();
