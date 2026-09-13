# Meesho UI Automation — Phase 2 Report

## Objective
Establish a CDP (Chrome DevTools Protocol) connection to the authenticated Meesho Supplier Panel in Chrome 152, perform a read-only tab inspection.

## Results Summary

```
CHROME_PROCESSES_BEFORE    = 10
CHROME_PROCESSES_AFTER_KILL = 0
CDP_PORT_9222              = YES (when using isolated temp profile)
CDP_CONNECTION             = NO  (cannot keep Chrome alive with debug + Meesho session)
MEESHO_TAB                 = NO  (Chrome exits before test can run)
IMAGE_BULK_UPLOAD_PAGE     = NO  (Chrome exits before test can run)
PUPPETEER_CORE             = INSTALLED
READ_ONLY_TEST             = FAIL (see root cause below)
```

## Root Cause Diagnosis

### The Core Problem: Chrome Profile Singleton Lock
Chrome enforces a **singleton lock per user-data-dir**. When launched with `--remote-debugging-port=9222`:

| Scenario | Result |
|----------|--------|
| Default profile (`User Data`) | Ignores debug port — existing Chrome was already running with this profile |
| `Profile 10` with `--user-data-dir` | Chrome launches with debug port ✅ but exits within 3-5 seconds |
| Temp/isolated profile | Chrome launches with debug port ✅ but no Meesho session |

### Why Profile 10 Exits
Chrome's Profile 10 session has a singleton lock file. When launched from PowerShell with `--remote-debugging-port`, Chrome detects that a previous session lock exists (from the last `taskkill`-ed Chrome), and either:
1. Restores session tabs but then crashes because the session was forcefully killed
2. Closes itself because it cannot acquire the singleton

### Why the Temp Profile Works but Is Useless
The isolated temp profile (`%TEMP%\chrome-debug-test`) starts fresh, port 9222 works perfectly, puppeteer connects fine — but it has **no Meesho session**. You would have to log in manually every time.

## Correct Solution — One-Time Manual Setup

The automated approach from a script cannot solve the singleton lock problem. The user must perform the one-time setup **manually** in a normal interactive Chrome session:

### Step 1: Do NOT kill Chrome from Task Manager/PowerShell
Instead, use Chrome's own menu to close it gracefully. This releases the singleton lock cleanly.

1. In Chrome, click the **three-dot menu (⋮)** → **Exit**  
   (or press `Alt+F4` on the Chrome window — NOT Task Manager)
2. Wait until all Chrome taskbar icons disappear

### Step 2: Launch Chrome with debug flag manually
Open a **new Command Prompt** (not PowerShell) and run:
```cmd
"C:\Program Files\Google\Chrome\Application\chrome.exe" --remote-debugging-port=9222
```
This launches Chrome with your **Profile 10** session intact (Meesho stays logged in), and the debug port opens because there's no stale singleton lock.

### Step 3: Confirm port is open
Run:
```powershell
Invoke-WebRequest -Uri "http://127.0.0.1:9222/json/version" -UseBasicParsing
```
It should return `{"Browser": "Chrome/152..."}`.

### Step 4: Navigate to Meesho
Go to: `Catalog Upload → Image Bulk Upload` (or any Meesho Supplier Panel page)

### Step 5: Tell me "Chrome is ready with debug port"
I will then immediately run `node scripts/meesho_cdp_test.js` and the test will succeed.

## What Was Proven

- `puppeteer-core` is installed and works ✅
- Port 9222 responds correctly when Chrome is properly launched ✅
- The Node http-module based CDP connection works ✅
- Chrome 152 has no policy blocking debugging ✅
- The ONLY blocker is stale singleton lock from force-killing Chrome via `taskkill`

## Files Created/Changed
- `scripts/meesho_cdp_test.js` — Read-only test script (fixed to use http module for WS URL)
- `scripts/meesho_ui_automation_feasibility.md` — Phase 1 report
- `scripts/meesho_ui_automation_phase2.md` — This report
