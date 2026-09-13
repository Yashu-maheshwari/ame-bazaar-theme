"""
Meesho Image Link Automation Pipeline (Playwright)
--------------------------------------------------
Automates the safe upload of image batch ZIPs to Meesho Supplier Panel,
retrieval of official Meesho image links directly from DOM table,
validation against project manifests, and update of link-state.

Safety Rules:
- Uses dedicated persistent profile: scripts/meesho_browser_profile
- Launches visible installed Chrome (channel="chrome")
- NEVER attaches to, modifies, kills, or uses personal Chrome profile
- User manually handles OTP / CAPTCHA / login challenges
- Strictly reads DOM; does NOT call hidden APIs or use clipboard
- Validates exact counts and SKU membership before saving
"""

import os
import sys
import json
import zipfile
import re
import argparse
import asyncio
from datetime import datetime
from playwright.async_api import async_playwright

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
ROOT_DIR = os.path.dirname(BASE_DIR)
PROFILE_DIR = os.path.join(BASE_DIR, "meesho_browser_profile")
ZIP_DIR = os.path.join(BASE_DIR, "meesho_image_export_package", "zips")
BATCH_STATE_FILE = os.path.join(BASE_DIR, "meesho-image-batch-state.json")
LINK_STATE_FILE = os.path.join(BASE_DIR, "meesho-image-link-state.json")
MEESHO_SUPPLIER_URL = "https://supplier.meesho.com"
MEESHO_IMAGE_BULK_URL = "https://supplier.meesho.com/panel/v2/new/catalog-upload/bulk/image-bulk-upload"

def load_json(path):
    if os.path.exists(path):
        with open(path, "r", encoding="utf-8") as f:
            return json.load(f)
    return {}

def save_json(path, data):
    with open(path, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2)

def parse_sku_and_slot(filename: str):
    """
    Matches the exact logic of the existing capture engine:
    Extracts SKU and slot from filenames like P-0061_1.jpg or P-0061_1-Copy.jpg
    """
    base = os.path.splitext(filename)[0]
    base = re.sub(r"-Copy$", "", base, flags=re.IGNORECASE)
    slot = 1
    m = re.search(r"_(\d+)$", base)
    if m:
        slot = int(m.group(1))
        base = re.sub(r"_\d+$", "", base)
    if base:
        return {"sku": base.upper(), "slot": slot}
    return None

def verify_batch_zip(batch_id: str):
    """
    Verifies that the physical zip exists and matches batch state.
    """
    batch_state = load_json(BATCH_STATE_FILE)
    batches = batch_state.get("batches", [])
    batch = next((b for b in batches if b["id"] == batch_id), None)
    if not batch:
        return {"success": False, "error": f"Batch {batch_id} not found in {BATCH_STATE_FILE}"}

    zip_path = os.path.join(ZIP_DIR, f"{batch_id}.zip")
    if not os.path.exists(zip_path):
        return {"success": False, "error": f"ZIP file {zip_path} does not exist physically"}

    try:
        with zipfile.ZipFile(zip_path, "r") as z:
            namelist = z.namelist()
            expected_count = batch.get("image_count", 50)
            if len(namelist) != expected_count:
                return {
                    "success": False,
                    "error": f"ZIP count mismatch: found {len(namelist)} files, expected {expected_count}"
                }
            
            # Check filename set equality
            expected_filenames = set(batch.get("filenames", []))
            actual_filenames = set(namelist)
            missing = expected_filenames - actual_filenames
            extra = actual_filenames - expected_filenames

            if missing or extra:
                return {
                    "success": False,
                    "error": f"ZIP membership mismatch. Missing: {missing}, Extra: {extra}"
                }

            return {
                "success": True,
                "batch": batch,
                "zip_path": zip_path,
                "file_count": len(namelist),
                "filenames": namelist
            }
    except Exception as e:
        return {"success": False, "error": f"Failed to inspect ZIP: {str(e)}"}

async def run_dry_run(batch_id="Batch_01"):
    print(f"\n==================================================")
    print(f" MEESHO PLAYWRIGHT PIPELINE - DRY RUN: {batch_id}")
    print(f"==================================================\n")

    # 1. Verify profile directory
    print(f"[1/5] Verifying persistent browser profile directory...")
    os.makedirs(PROFILE_DIR, exist_ok=True)
    print(f"      Profile dir: {PROFILE_DIR} (OK)")

    # 2. Verify ZIP file and 50 files
    print(f"\n[2/5] Verifying physical ZIP package...")
    zip_check = verify_batch_zip(batch_id)
    if not zip_check["success"]:
        print(f"      [FAILED] {zip_check['error']}")
        return False
    print(f"      ZIP file: {zip_check['zip_path']}")
    print(f"      Files in ZIP: {zip_check['file_count']} (Expected {zip_check['batch']['image_count']})")
    print(f"      Sample files: {zip_check['filenames'][:3]} ... {zip_check['filenames'][-2:]}")

    # 3. Verify all 50 SKUs against manifest
    print(f"\n[3/5] Verifying SKU parsing and membership...")
    parsed_skus = []
    for f in zip_check["filenames"]:
        p = parse_sku_and_slot(f)
        if not p:
            print(f"      [FAILED] Could not parse SKU from {f}")
            return False
        parsed_skus.append(p["sku"])
    print(f"      Parsed {len(parsed_skus)} SKUs successfully.")
    print(f"      Sample SKUs: {parsed_skus[:3]} ... {parsed_skus[-2:]}")

    # 4. Launch visible browser with persistent context
    print(f"\n[4/5] Launching visible Chrome via Playwright persistent context...")
    print(f"      Using dedicated profile: {PROFILE_DIR}")
    print(f"      (Normal Chrome User Data is NOT touched)")
    
    async with async_playwright() as p:
        context = await p.chromium.launch_persistent_context(
            user_data_dir=PROFILE_DIR,
            channel="chrome",
            headless=False,
            args=["--start-maximized"],
            no_viewport=True
        )
        
        page = context.pages[0] if context.pages else await context.new_page()
        
        print(f"\n[5/5] Testing navigation to Meesho Supplier Panel...")
        await page.goto(MEESHO_SUPPLIER_URL)
        await page.wait_for_timeout(3000)
        
        current_url = page.url
        title = await page.title()
        print(f"      Current URL: {current_url}")
        print(f"      Page Title:  {title}")
        
        # Check login status
        is_login_page = "login" in current_url.lower()
        if is_login_page:
            print(f"\n      STATUS: Login page detected.")
            print(f"      [MANUAL AUTH REQUIRED]: User must log in manually to save session in persistent profile.")
        else:
            print(f"\n      STATUS: Authenticated session detected (not on login page).")

        print(f"\n      [DRY RUN SAFEGUARD]: No files uploaded. No Meesho data modified.")
        print(f"      Closing browser context gracefully...")
        await context.close()
        
    print(f"\n==================================================")
    print(f" DRY RUN COMPLETED SUCCESSFULLY")
    print(f"==================================================\n")
    return True

async def run_batch_upload(batch_id="Batch_01"):
    """
    Executes visible automation for a single batch.
    """
    print(f"\n==================================================")
    print(f" MEESHO PLAYWRIGHT PIPELINE - EXECUTE: {batch_id}")
    print(f"==================================================\n")

    # Step 1: Pre-flight checks
    zip_check = verify_batch_zip(batch_id)
    if not zip_check["success"]:
        print(f"[FATAL] Pre-flight failed: {zip_check['error']}")
        return False

    batch = zip_check["batch"]
    zip_path = zip_check["zip_path"]
    expected_filenames = set(zip_check["filenames"])
    expected_count = batch["image_count"]

    print(f"[PRE-FLIGHT OK] {batch_id} with {expected_count} files verified at {zip_path}")

    link_state = load_json(LINK_STATE_FILE)
    if "products" not in link_state:
        link_state["products"] = {}
    if "audit_log" not in link_state:
        link_state["audit_log"] = []

    # Step 2: Launch browser
    async with async_playwright() as p:
        context = await p.chromium.launch_persistent_context(
            user_data_dir=PROFILE_DIR,
            channel="chrome",
            headless=False,
            args=["--start-maximized"],
            no_viewport=True
        )
        
        page = context.pages[0] if context.pages else await context.new_page()
        
        print(f"[NAVIGATE] Opening Meesho Supplier Panel...")
        await page.goto(MEESHO_SUPPLIER_URL)
        await page.wait_for_timeout(3000)

        # Step 3: Check if login is required
        if "login" in page.url.lower():
            print("\n" + "!" * 60)
            print("HUMAN ACTION REQUIRED: Please log into Meesho Supplier Panel.")
            print("Complete any OTP/CAPTCHA in the visible Chrome window.")
            print("The pipeline will automatically resume once logged in.")
            print("!" * 60 + "\n")
            
            # Wait for user to complete login (up to 5 minutes)
            logged_in = False
            for _ in range(60):
                await page.wait_for_timeout(5000)
                if "login" not in page.url.lower() and "supplier.meesho.com" in page.url:
                    logged_in = True
                    break
            
            if not logged_in:
                print("[ERROR] Login timeout exceeded (5 minutes). Aborting.")
                await context.close()
                return False

        print("[AUTH] Successfully logged in! Session preserved in persistent profile.")

        # Step 4: Navigate to Image Bulk Upload
        print(f"[NAVIGATE] Navigating to Image Bulk Upload page...")
        await page.goto(MEESHO_IMAGE_BULK_URL)
        await page.wait_for_timeout(5000)

        # Locate file input
        file_input = await page.query_selector('input[type="file"]')
        if not file_input:
            print("[ERROR] Could not find file input element (input[type='file']) on Image Bulk Upload page.")
            await context.close()
            return False

        print(f"[UPLOAD] Setting input file to {zip_path}...")
        await file_input.set_input_files(zip_path)
        print("[UPLOAD] File selected. Waiting for upload processing...")

        # Wait for processing and "Get Image Link" button
        # Wait up to 2 minutes for processing
        get_link_btn = None
        for _ in range(24):
            await page.wait_for_timeout(5000)
            # Find button with text 'Get Image Link'
            btn = await page.query_selector("button:has-text('Get Image Link'), input[value*='Get Image Link']")
            if btn and await btn.is_visible():
                get_link_btn = btn
                break

        if not get_link_btn:
            print("[ERROR] 'Get Image Link' button not found or not active after 2 minutes.")
            await context.close()
            return False

        print("[ACTION] Clicking 'Get Image Link'...")
        await get_link_btn.click()
        await page.wait_for_timeout(5000)

        # Step 5: Read DOM Table
        print("[SCRAPE] Reading generated Image Links table directly from DOM...")
        extracted_rows = await page.evaluate('''() => {
            const rows = [];
            // Look for table rows containing official meesho image links
            const trs = document.querySelectorAll('table tr');
            trs.forEach(tr => {
                const text = tr.innerText || '';
                const linkEl = tr.querySelector('a[href*="meeshosupplyassets.com"]') || tr.querySelector('input[value*="meeshosupplyassets.com"]');
                let url = '';
                if (linkEl) {
                    url = linkEl.href || linkEl.value || '';
                } else {
                    const m = text.match(/https:\\/\\/upload\\.meeshosupplyassets\\.com\\/cataloging\\/[^\\s\\t]+/);
                    if (m) url = m[0];
                }
                if (url) {
                    rows.push({
                        row_text: text,
                        url: url
                    });
                }
            });
            return rows;
        }''')

        print(f"[SCRAPE] Found {len(extracted_rows)} rows containing official Meesho URLs in DOM.")

        # Step 6: Validate extracted links
        stats = {
            "total_extracted": len(extracted_rows),
            "valid_urls": 0,
            "matched_skus": 0,
            "foreign_skus": 0,
            "unknown_skus": 0,
            "invalid_urls": 0,
            "duplicates": 0,
            "missing": []
        }

        seen_urls = set()
        seen_filenames = set()
        processed_mappings = []

        for row in extracted_rows:
            url = row["url"]
            if not url.startswith("https://upload.meeshosupplyassets.com/cataloging/"):
                stats["invalid_urls"] += 1
                continue
            if url in seen_urls:
                stats["duplicates"] += 1
                continue
            seen_urls.add(url)
            stats["valid_urls"] += 1

            filename = url.split("/")[-1]
            p = parse_sku_and_slot(filename)
            if not p:
                stats["unknown_skus"] += 1
                continue

            sku = p["sku"]
            slot = p["slot"]

            if filename not in expected_filenames:
                stats["foreign_skus"] += 1
                continue

            if filename in seen_filenames:
                stats["duplicates"] += 1
                continue
            seen_filenames.add(filename)

            stats["matched_skus"] += 1
            processed_mappings.append({"sku": sku, "slot": slot, "filename": filename, "url": url})

        for f in expected_filenames:
            if f not in seen_filenames:
                stats["missing"].append(f)

        print(f"\n--- VALIDATION RESULTS ---")
        print(f"Valid URLs:    {stats['valid_urls']} / {expected_count}")
        print(f"Matched SKUs:  {stats['matched_skus']} / {expected_count}")
        print(f"Foreign SKUs:  {stats['foreign_skus']}")
        print(f"Unknown SKUs:  {stats['unknown_skus']}")
        print(f"Duplicates:    {stats['duplicates']}")
        print(f"Missing Files: {len(stats['missing'])}")

        is_clean = (
            stats["valid_urls"] == expected_count and
            stats["matched_skus"] == expected_count and
            stats["foreign_skus"] == 0 and
            stats["unknown_skus"] == 0 and
            stats["duplicates"] == 0 and
            len(stats["missing"]) == 0
        )

        if not is_clean:
            print("\n[BLOCKED] Validation failed! URLs will NOT be saved.")
            await context.close()
            return False

        print("\n[SUCCESS] 100% Exact Match & Clean! Saving verified links to state...")

        # Step 7: Save to meesho-image-link-state.json
        now_iso = datetime.utcnow().isoformat() + "Z"
        for m in processed_mappings:
            target_sku = m["sku"]
            # Find matching product key (case-insensitive)
            matched_key = next((k for k in link_state["products"] if k.upper() == target_sku), target_sku)
            
            if matched_key not in link_state["products"]:
                link_state["products"][matched_key] = {"sku": matched_key}
            
            prod = link_state["products"][matched_key]
            if "meesho_image_urls" not in prod:
                prod["meesho_image_urls"] = []
            
            url = m["url"]
            if url not in prod["meesho_image_urls"]:
                if m["slot"] == 1:
                    prod["meesho_image_urls"].insert(0, url)
                    prod["meesho_image_url"] = url
                else:
                    prod["meesho_image_urls"].append(url)
            
            prod["status"] = "VERIFIED_MEESHO_URL"
            prod["updated_at"] = now_iso

            link_state["audit_log"].append({
                "timestamp": now_iso,
                "action": "IMPORT_BATCH_IMAGE_LINK_PLAYWRIGHT",
                "batch_id": batch_id,
                "sku": matched_key,
                "slot": m["slot"],
                "filename": m["filename"],
                "url": url
            })

        save_json(LINK_STATE_FILE, link_state)

        # Update batch state
        batch_state = load_json(BATCH_STATE_FILE)
        for b in batch_state.get("batches", []):
            if b["id"] == batch_id:
                b["status"] = "VERIFIED"
                b["captured_sku_count"] = stats["matched_skus"]
                b["updated_at"] = now_iso
                break
        save_json(BATCH_STATE_FILE, batch_state)

        print(f"[STATE SAVED] {batch_id} marked VERIFIED with {stats['matched_skus']} images linked.")
        await context.close()
        return True

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Meesho Playwright Image Link Pipeline")
    parser.add_argument("--batch", default="Batch_01", help="Batch ID to process (default: Batch_01)")
    parser.add_argument("--dry-run", action="store_true", default=False, help="Run in dry-run mode (no upload)")
    parser.add_argument("--execute", action="store_true", default=False, help="Execute real upload and link capture")
    
    args = parser.parse_args()
    
    # Default to dry-run unless --execute is explicitly specified
    if args.execute:
        asyncio.run(run_batch_upload(args.batch))
    else:
        asyncio.run(run_dry_run(args.batch))
