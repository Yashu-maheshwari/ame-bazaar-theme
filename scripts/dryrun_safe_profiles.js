const fs = require('fs');

const STATE_FILE = 'scripts/meesho-profile-state.json';
const SAFETY_FILE = 'meesho_profile_safety_results.json';
const MATRIX_FILE = 'meesho_template_field_matrix.json';

function run() {
    const state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    const safety = JSON.parse(fs.readFileSync(SAFETY_FILE, 'utf8'));
    let matrix = {};
    if (fs.existsSync(MATRIX_FILE)) {
        matrix = JSON.parse(fs.readFileSync(MATRIX_FILE, 'utf8'));
    }

    let profiles = state.profiles;
    let safetyMap = {};
    safety.profile_details.forEach(p => {
        safetyMap[p.profile] = p;
    });

    let results = {
        total: Object.keys(profiles).length,
        READY_FOR_APPROVAL: 0,
        NO_ACTION_REQUIRED: 0,
        NEEDS_REVIEW: 0,
        products_ready: 0,
        products_no_action: 0,
        products_needs_review: 0,
        details: []
    };

    let exactAttributesToApply = new Set();

    for (let pid in profiles) {
        let p = profiles[pid];
        let originalStatus = safetyMap[pid] ? safetyMap[pid].status : 'INVALID';
        let status = originalStatus;

        let attrsToApply = [];
        let evidenceList = new Set();

        for (let key in p.attributes) {
            let attr = p.attributes[key];
            if (attr.value && attr.value !== 'HUMAN_REQUIRED') {
                attrsToApply.push(`${key}: ${attr.value}`);
                evidenceList.add(attr.evidence_level);
                exactAttributesToApply.add(key);
            }
        }

        if (status === 'SAFE_TO_APPROVE') {
            if (attrsToApply.length === 0) {
                status = 'NO_ACTION_REQUIRED';
            } else {
                status = 'READY_FOR_APPROVAL';
            }
        }

        if (status === 'READY_FOR_APPROVAL') {
            results.READY_FOR_APPROVAL++;
            results.products_ready += p.product_count;
        } else if (status === 'NO_ACTION_REQUIRED') {
            results.NO_ACTION_REQUIRED++;
            results.products_no_action += p.product_count;
        } else {
            results.NEEDS_REVIEW++;
            results.products_needs_review += p.product_count;
        }

        results.details.push({
            profile_id: pid,
            category: p.category,
            product_count: p.product_count,
            skus_sample: p.skus.slice(0, 3).join(', ') + (p.skus.length > 3 ? '...' : ''),
            attributes_to_apply: attrsToApply.length > 0 ? attrsToApply.join(', ') : 'None',
            evidence: evidenceList.size > 0 ? Array.from(evidenceList).join(', ') : 'N/A',
            status: status
        });
    }

    fs.writeFileSync('meesho_safe_profile_dryrun.json', JSON.stringify(results, null, 2));

    let md = `# Meesho Safe Profile Dry-Run Preview

## Summary
* Total Profiles: ${results.total}
* READY_FOR_APPROVAL: ${results.READY_FOR_APPROVAL} (covers ${results.products_ready} products)
* NO_ACTION_REQUIRED: ${results.NO_ACTION_REQUIRED} (covers ${results.products_no_action} products)
* NEEDS_REVIEW: ${results.NEEDS_REVIEW} (covers ${results.products_needs_review} products)

## Attributes Targeted for Application
The following attributes have been safely extracted and are ready to be applied across the approved profiles:
${Array.from(exactAttributesToApply).map(a => `- **${a.toUpperCase()}**`).join('\n')}

## Profile Dry-Run Details
| Profile | Category | Products | Attributes to Apply | Evidence | Status |
|---------|----------|----------|---------------------|----------|--------|
`;

    results.details.sort((a,b) => {
        if(a.status !== b.status) return a.status.localeCompare(b.status);
        return b.product_count - a.product_count;
    }).forEach(d => {
        md += `| ${d.profile_id} | ${d.category} | ${d.product_count} | ${d.attributes_to_apply} | ${d.evidence} | ${d.status} |\n`;
    });

    fs.writeFileSync('meesho_safe_profile_dryrun.md', md);
    console.log("Dry-run complete.");
}

run();
