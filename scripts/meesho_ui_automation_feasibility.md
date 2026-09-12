# Meesho UI Automation Feasibility Report — Phase 1

## Available Browser Automation Capabilities

| Capability | Status | Details |
|------------|--------|---------|
| Chrome DevTools MCP (as active MCP tool) | ❌ NOT REGISTERED | Only `ame-bazaar-gbp-mcp` is in `mcp_config.json`. The chrome-devtools-mcp plugin is installed as a skill/reference but not as an active MCP server I can call via `call_mcp_tool`. |
| `chrome-devtools-mcp` CLI | ✅ AVAILABLE | Installable/runnable via `npx chrome-devtools-mcp@latest`. Supports `--autoConnect`, `--browserUrl`, and `--wsEndpoint` modes. |
| Puppeteer (Node module) | ❌ NOT INSTALLED | `require('puppeteer')` returns `MODULE_NOT_FOUND`. |
| puppeteer-core | ❌ NOT INSTALLED | Could be installed via npm. Allows connecting to existing Chrome via CDP without downloading Chromium. |

## Existing Chrome Instance

| Check | Result |
|-------|--------|
| Chrome running | ✅ YES (multiple processes detected) |
| Remote debugging port (9222) | ❌ NOT OPEN |
| Remote debugging port (9223) | ❌ NOT OPEN |
| `--remote-debugging-port` in launch args | ❌ NOT PRESENT |
| Chrome user data directory exists | ✅ YES (`%LOCALAPPDATA%\Google\Chrome\User Data`) |
| Custom `--user-data-dir` | ❌ NOT SPECIFIED (using default) |

## Connection Strategies Evaluated

### Strategy A: Attach to Existing Chrome via CDP
**Result: ❌ NOT POSSIBLE without user action.**

Chrome was launched normally (without `--remote-debugging-port`). CDP/DevTools protocol requires the browser to be started with debugging enabled. You cannot retroactively enable debugging on a running Chrome instance.

**Required user action:** Close Chrome completely and relaunch with:
```
"C:\Program Files\Google\Chrome\Application\chrome.exe" --remote-debugging-port=9222
```
This preserves the existing user profile (including Meesho login session) because it uses the same default user data directory.

### Strategy B: Launch New Chrome with Same Profile via puppeteer-core
**Result: ❌ NOT POSSIBLE while Chrome is running.**

Chrome locks its user data directory. You cannot open a second Chrome instance using the same profile directory. Attempting to do so either fails or opens in an inconsistent state.

### Strategy C: Add chrome-devtools-mcp to mcp_config.json
**Result: ⚠️ POSSIBLE but requires restart.**

Adding `chrome-devtools-mcp` as an MCP server would give me direct `take_snapshot`, `click`, `fill`, `navigate_page`, `evaluate_script`, and file upload capabilities. However:
1. It requires modifying `mcp_config.json`
2. It requires an Antigravity restart to load the new MCP server
3. Chrome still needs to have debugging enabled (Strategy A prerequisite)

### Strategy D: Install puppeteer-core and connect via script
**Result: ⚠️ POSSIBLE if Chrome is relaunched with debugging.**

Install `puppeteer-core` (lightweight, no Chromium download), write a Node.js automation script that connects to `http://127.0.0.1:9222` via CDP. This is the most flexible approach because:
- Full control over file upload (via `page.waitForFileChooser()` + `fileChooser.accept()`)
- Full control over clicking buttons
- Full control over reading DOM/table data
- Runs as a normal Node.js script alongside the existing Express server
- No MCP restart needed

## Feature-by-Feature Assessment (Assuming Chrome Debugging Enabled)

| Feature | Automatable? | Method |
|---------|-------------|--------|
| Navigate to Image Bulk Upload page | ✅ YES | `page.goto()` or click navigation |
| Choose ZIP file via file picker | ✅ YES | `page.waitForFileChooser()` + `fileChooser.accept(filePath)` |
| Wait for upload processing | ✅ YES | `page.waitForSelector()` or polling |
| Click "Get Image Link" | ✅ YES | `page.click()` on visible button |
| Read Image Links table | ✅ YES | `page.evaluate()` to extract DOM table rows |
| Detect CAPTCHA/OTP/security challenge | ✅ YES | Check for known challenge selectors, pause if detected |
| Handle CAPTCHA/OTP automatically | ❌ NO (by design) | Must pause and alert user |

## Feasibility Summary

```
ATTACH_EXISTING_CHROME     = NO  (debugging not enabled)
MEESHO_TAB_DETECTED        = N/A (cannot connect)
FILE_UPLOAD_UI_ACCESSIBLE  = N/A (cannot connect)
GET_IMAGE_LINK_UI_ACCESSIBLE = N/A (cannot connect)
SAFE_AUTOMATION_FEASIBLE   = YES (after one-time Chrome relaunch with debugging flag)
```

## Required One-Time Setup (User Action)

To enable the full automation pipeline, the user must perform ONE manual step:

1. **Close all Chrome windows completely** (ensure no chrome.exe processes remain)
2. **Relaunch Chrome with remote debugging:**
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --remote-debugging-port=9222
   ```
3. **Log into Meesho Supplier Panel normally** (if session expired)
4. **Navigate to any page** — the automation will handle navigation from there

After this, the automation script can:
- Connect to Chrome via `http://127.0.0.1:9222`
- Find or navigate to the Meesho Supplier Panel
- Upload ZIPs, click buttons, read tables
- Pause on any security challenge
- Return data to the local validation pipeline

## Recommended Implementation Plan

1. Install `puppeteer-core` in the scripts directory
2. Build `scripts/meesho_image_auto_runner.js` using puppeteer-core CDP connection
3. Integrate with existing batch state management
4. Add a dashboard panel showing automation status
5. Test with Batch_01 only
6. Scale to remaining batches after successful test

## Parts Requiring Human Intervention

Even with full automation, the following will ALWAYS require human action:
- Initial Chrome relaunch with debugging flag (one-time)
- Meesho login if session expires
- CAPTCHA completion if Meesho presents one
- OTP verification if triggered
- Any KYC or suspicious-activity challenge
- Final approval before bulk-saving verified URLs (optional safety gate)
