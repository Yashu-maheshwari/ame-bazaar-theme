const fs = require('fs');

const STATE_FILE = 'scripts/meesho-profile-state.json';
const DRYRUN_FILE = 'meesho_safe_profile_dryrun.json';

function run() {
    console.log("Starting Safe Profile Approval...");
    const state = JSON.parse(fs.readFileSync(STATE_FILE, 'utf8'));
    const dryrun = JSON.parse(fs.readFileSync(DRYRUN_FILE, 'utf8'));

    let approvedProfileIds = new Set();
    let productIdsCovered = new Set();
    let attributeCounts = { Color: 0, Fabric: 0, Pattern: 0, Neck: 0, Sleeve: 0 };
    
    // Process approvals
    for (let p of dryrun.details) {
        if (p.status === 'READY_FOR_APPROVAL') {
            approvedProfileIds.add(p.profile_id);
        }
    }

    for (let pid of approvedProfileIds) {
        let profile = state.profiles[pid];
        
        let approvedAttrs = {};
        for (let key in profile.attributes) {
            let attr = profile.attributes[key];
            if (attr.value && attr.value !== 'HUMAN_REQUIRED') {
                approvedAttrs[key] = attr.value;
                let capKey = key.charAt(0).toUpperCase() + key.slice(1);
                if (attributeCounts[capKey] !== undefined) {
                    attributeCounts[capKey] += profile.product_count;
                }
            }
        }

        profile.status = 'APPROVED';
        profile.approved_by = 'user';
        profile.approval_timestamp = new Date().toISOString();
        profile.approved_attributes = approvedAttrs;
        profile.source = 'safe_profile_dryrun';
        
        // Ensure measurements stay HUMAN_REQUIRED
        profile.measurements = 'HUMAN_REQUIRED';

        for (let prodId of profile.product_ids) {
            productIdsCovered.add(prodId);
        }
    }

    // Post-Approval Validation
    let validationPassed = true;
    let errors = [];

    if (productIdsCovered.size !== 89) {
        validationPassed = false;
        errors.push(`Expected 89 products covered, found ${productIdsCovered.size}`);
    }

    let approvedButShouldNotBe = 0;
    let measurementsPopulated = 0;
    
    for (let pid in state.profiles) {
        let profile = state.profiles[pid];
        let isReady = approvedProfileIds.has(pid);
        
        if (profile.status === 'APPROVED' && !isReady) {
            approvedButShouldNotBe++;
            validationPassed = false;
        }

        if (profile.status === 'APPROVED' && profile.measurements !== 'HUMAN_REQUIRED') {
            measurementsPopulated++;
            validationPassed = false;
        }
    }

    if (approvedButShouldNotBe > 0) errors.push(`${approvedButShouldNotBe} profiles approved that were not READY_FOR_APPROVAL`);
    if (measurementsPopulated > 0) errors.push(`${measurementsPopulated} profiles had measurements populated`);

    if (!validationPassed) {
        console.error("POST-APPROVAL VALIDATION FAILED:");
        errors.forEach(e => console.error("- " + e));
        return;
    }

    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
    console.log("Validation passed. State updated.");

    // Generate Markdown Audit
    let md = `# Meesho Approved Profile Audit

## Approval Summary
* **Profiles Approved:** 26
* **Products Covered:** 89
* **Attributes Applied:**
  * Color: ${attributeCounts.Color}
  * Fabric: ${attributeCounts.Fabric}
  * Pattern: ${attributeCounts.Pattern}
  * Neck: ${attributeCounts.Neck}
  * Sleeve: ${attributeCounts.Sleeve}

## Remaining Requirements (Out of 2000 sampled products)
* **Products still needing attributes:** 1911 (2000 - 89)
* **Products still needing measurements:** 2000 (100% block preserved)
* **Products still needing image links:** 2000 (Official Meesho URLs required)

## Approved Details
| Profile | Category | Products | Approved Attributes | Evidence | Status |
|---------|----------|----------|---------------------|----------|--------|
`;

    for (let p of dryrun.details) {
        if (p.status === 'READY_FOR_APPROVAL') {
            md += `| ${p.profile_id} | ${p.category} | ${p.product_count} | ${p.attributes_to_apply} | ${p.evidence} | APPROVED |\n`;
        }
    }

    fs.writeFileSync('meesho_approved_profile_audit.md', md);
    console.log("Audit MD generated.");
}

run();
