const express = require('express');
const fs = require('fs');
const path = require('path');

const router = express.Router();
const STATE_FILE = path.join(__dirname, 'meesho-size-chart-state.json');
const PROFILE_STATE_FILE = path.join(__dirname, 'meesho-profile-state.json');

const CATEGORY_REQUIREMENTS = {
    'TSHIRT': ['Chest Size', 'Length Size', 'Shoulder Size'],
    'FROCK': ['Bust Size', 'Waist Size', 'Length'],
    'CLOTHING_SET': ['Top Chest', 'Bottom Waist'],
    'JEANS': ['Waist', 'Inseam', 'Rise', 'Hip', 'Thigh']
};

function getState() {
    if (!fs.existsSync(STATE_FILE)) return { profiles: {} };
    return JSON.parse(fs.readFileSync(STATE_FILE, 'utf-8'));
}

function saveState(state) {
    fs.writeFileSync(STATE_FILE, JSON.stringify(state, null, 2));
}

// Get all size charts
router.get('/', (req, res) => {
    res.json(getState());
});

// Create or update a DRAFT size chart
router.post('/', (req, res) => {
    let state = getState();
    let { profile_id, category, measurements } = req.body;
    
    if (!profile_id) {
        profile_id = `VERIFIED_SIZE_CHART_${Date.now()}`;
    }

    state.profiles[profile_id] = {
        profile_id,
        category,
        source: 'user_verified',
        status: 'DRAFT',
        verified: false,
        measurements: measurements || {}
    };

    saveState(state);
    res.json({ success: true, profile_id });
});

// Verify and Approve a size chart
router.post('/approve', (req, res) => {
    let state = getState();
    let { profile_id } = req.body;

    let profile = state.profiles[profile_id];
    if (!profile) return res.status(404).json({ error: 'Profile not found' });

    // Validation
    let reqs = CATEGORY_REQUIREMENTS[profile.category];
    if (!reqs) return res.status(400).json({ error: 'Unsupported category' });

    let sizeCount = Object.keys(profile.measurements).length;
    if (sizeCount === 0) return res.status(400).json({ error: 'Incomplete size chart (no sizes)' });

    for (let size in profile.measurements) {
        let sizeData = profile.measurements[size];
        for (let reqField of reqs) {
            let val = sizeData[reqField];
            if (val === undefined || val === null || val === '') {
                return res.status(400).json({ error: `Blank required measurement: ${reqField} for size ${size}` });
            }
            if (isNaN(Number(val)) || Number(val) < 0) {
                return res.status(400).json({ error: `Non-numeric or negative measurement: ${reqField} = ${val} for size ${size}` });
            }
        }
    }

    profile.status = 'VERIFIED';
    profile.verified = true;
    profile.approved_by = 'user';
    profile.approved_at = new Date().toISOString();

    saveState(state);
    res.json({ success: true, profile });
});

// Apply a VERIFIED size chart to a product batch profile
router.post('/apply', (req, res) => {
    let state = getState();
    let { size_chart_id, batch_profile_id } = req.body;

    let sc = state.profiles[size_chart_id];
    if (!sc || sc.status !== 'VERIFIED') return res.status(400).json({ error: 'Size chart not VERIFIED or not found' });

    let profileState = JSON.parse(fs.readFileSync(PROFILE_STATE_FILE, 'utf-8'));
    let batch = profileState.profiles[batch_profile_id];

    if (!batch) return res.status(404).json({ error: 'Batch profile not found' });
    if (batch.category !== sc.category) {
        return res.status(400).json({ error: `Category mismatch: Cannot apply ${sc.category} chart to ${batch.category} batch` });
    }

    // Assign to batch
    batch.measurements = {
        assigned_size_chart: size_chart_id,
        status: 'VERIFIED_APPLIED',
        applied_at: new Date().toISOString(),
        approved_by: 'user'
    };

    // Log to Audit Trail
    if (!profileState.audit_trail) profileState.audit_trail = [];
    profileState.audit_trail.push({
        action: 'APPLY_SIZE_CHART',
        batch_profile_id: batch_profile_id,
        size_chart_id: size_chart_id,
        category: batch.category,
        timestamp: new Date().toISOString(),
        source: 'user_verified'
    });

    fs.writeFileSync(PROFILE_STATE_FILE, JSON.stringify(profileState, null, 2));

    res.json({ success: true, message: `Successfully applied ${size_chart_id} to ${batch_profile_id}` });
});

// Export requirements for the frontend
router.get('/requirements', (req, res) => {
    res.json(CATEGORY_REQUIREMENTS);
});

module.exports = router;
