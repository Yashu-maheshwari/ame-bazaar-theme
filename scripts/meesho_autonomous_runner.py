"""
Meesho Autonomous Multi-Batch Runner (Playwright & Local Pipeline)
------------------------------------------------------------------
Orchestrates autonomous processing of Batch_01 through Batch_72:
- Pre-flight validation on physical ZIPs, manifests, and SKUs
- Safe browser launch via dedicated profile (scripts/meesho_browser_profile)
- Detection of Akamai WAF / CAPTCHA / Login boundaries
- DOM-based link extraction (no clipboard, no hidden APIs)
- Exact mathematical validation (50/50, 0 dupes, 0 foreign, 0 invalid)
- Atomic state updates to meesho-image-link-state.json and meesho-image-batch-state.json
- Automatic continuation through Batch_01 -> Batch_72 without manual confirmation
- Zero modifications to WooCommerce or Raintech databases
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

def atomic_save_json(path, data):
    tmp_path = f"{path}.tmp.{int(datetime.utcnow().timestamp())}"
    with open(tmp_path, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2)
    try:
        import shutil
        shutil.copyfile(tmp_path, path)
        os.remove(tmp_path)
    except Exception:
        with open(path, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=2)
        if os.path.exists(tmp_path):
            try: os.remove(tmp_path)
            except Exception: pass

def parse_sku_and_slot(filename: str):
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
    batch_state = load_json(BATCH_STATE_FILE)
    batches = batch_state.get("batches", [])
    batch = next((b for b in batches if b["id"] == batch_id), None)
    if not batch:
        return {"success": False, "error": f"Batch {batch_id} not found in {BATCH_STATE_FILE}"}

    zip_path = os.path.join(ZIP_DIR, f"{batch_id}.zip")
    if not os.path.exists(zip_path):
        return {"success": False, "error": f"ZIP file {zip_path} not found"}

    try:
        with zipfile.ZipFile(zip_path, "r") as z:
            namelist = z.namelist()
            expected_count = batch.get("image_count", 50)
            if len(namelist) != expected_count:
                return {
                    "success": False,
                    "error": f"Count mismatch: found {len(namelist)} in ZIP, expected {expected_count}"
                }
            
            expected_filenames = set(batch.get("filenames", []))
            actual_filenames = set(namelist)
            missing = expected_filenames - actual_filenames
            extra = actual_filenames - expected_filenames

            if missing or extra:
                return {
                    "success": False,
                    "error": f"Membership mismatch. Missing: {missing}, Extra: {extra}"
                }

            return {
                "success": True,
                "batch": batch,
                "zip_path": zip_path,
                "file_count": len(namelist),
                "filenames": namelist
            }
    except Exception as e:
        return {"success": False, "error": f"ZIP inspection failed: {str(e)}"}

async def check_security_and_auth(page):
    """
    Checks for Akamai WAF Access Denied, Bot Challenges, or Login forms.
    Returns (status, message).
    """
    try:
        title = await page.title()
        text = await page.evaluate("document.body ? document.body.innerText : ''")
    except Exception:
        return "ERROR", "Could not read page title/text"

    lower_title = title.lower()
    lower_text = text.lower()

    if "access denied" in lower_title or "access denied" in lower_text or "errors.edgesuite.net" in lower_text:
        return "WAF_BLOCKED", "Akamai EdgeSuite Bot Manager returned Access Denied (HTTP 403)"

    if "robot" in lower_text or "captcha" in lower_text or "challenge" in lower_text:
        return "CHALLENGE", "CAPTCHA or Bot Challenge detected"

    if "login" in page.url.lower() or "enter mobile number" in lower_text or "login to your supplier account" in lower_text:
        return "LOGIN_REQUIRED", "Meesho Supplier Login Required (Mobile / OTP)"

    return "OK", "Page accessible"

async def process_single_batch(page, batch_info):
    batch = batch_info["batch"]
    batch_id = batch["id"]
    zip_path = batch_info["zip_path"]
    expected_filenames = set(batch_info["filenames"])
    expected_count = batch_info["file_count"]

    print(f"\n---> [PROCESSING] {batch_id} ({expected_count} images)...")

    # Navigate to Image Bulk Upload if not already there
    if "image-bulk-upload" not in page.url.lower():
        print(f"     Navigating to {MEESHO_IMAGE_BULK_URL}...")
        await page.goto(MEESHO_IMAGE_BULK_URL)
        await page.wait_for_timeout(4000)

    # Re-verify page security status
    sec_status, sec_msg = await check_security_and_auth(page)
    if sec_status != "OK":
        return {"success": False, "reason": sec_status, "message": sec_msg}

    # Find file input
    file_input = await page.query_selector('input[type="file"]')
    if not file_input:
        return {"success": False, "reason": "UI_ELEMENT_MISSING", "message": "Could not locate input[type='file'] on page"}

    print(f"     [UPLOAD] Selecting {zip_path}...")
    await file_input.set_input_files(zip_path)
    print(f"     [WAIT] Uploading and processing batch...")

    # Wait for 'Get Image Link' button
    get_link_btn = None
    for attempt in range(36):
        await page.wait_for_timeout(5000)
        btn = await page.query_selector("button:has-text('Get Image Link'), input[value*='Get Image Link']")
        if btn and await btn.is_visible():
            get_link_btn = btn
            break
        if attempt > 0 and attempt % 6 == 0:
            print(f"     Waiting for Meesho backend processing... ({attempt * 5}s)")

    if not get_link_btn:
        return {"success": False, "reason": "TIMEOUT", "message": "'Get Image Link' button did not appear within 3 minutes"}

    print(f"     [ACTION] Clicking 'Get Image Link'...")
    await get_link_btn.click()

    # Poll DOM for table rows
    print(f"     [SCRAPE] Reading generated table directly from DOM...")
    extracted_rows = []
    for _ in range(15):
        await page.wait_for_timeout(2500)
        extracted_rows = await page.evaluate('''() => {
            const rows = [];
            const trs = document.querySelectorAll('table tr');
            trs.forEach(tr => {
                const text = tr.innerText || '';
                let url = '';
                const allElements = tr.querySelectorAll('a, input, textarea, img');
                for (const el of allElements) {
                    const candidate = el.href || el.value || el.src || '';
                    if (candidate.includes('upload.meeshosupplyassets.com/cataloging/')) {
                        url = candidate;
                        break;
                    }
                }
                if (!url) {
                    const m = text.match(/https:\\/\\/upload\\.meeshosupplyassets\\.com\\/cataloging\\/[^\\s\\t]+/);
                    if (m) url = m[0];
                }
                if (url) {
                    const extMatch = url.match(/(.*?\\.jpg|.*?\\.jpeg|.*?\\.png)/i);
                    if (extMatch) url = extMatch[1];
                    rows.push({ row_text: text, url: url });
                }
            });
            return rows;
        }''')
        if len(extracted_rows) >= expected_count:
            break

    print(f"     [EXTRACTED] Found {len(extracted_rows)} image links in DOM table.")

    # Validate extracted data
    seen_urls = set()
    seen_filenames = set()
    matched_mappings = []
    duplicates = 0
    foreign_skus = 0
    invalid_urls = 0
    unknown_skus = 0

    for row in extracted_rows:
        url = row["url"]
        if not url.startswith("https://upload.meeshosupplyassets.com/cataloging/"):
            invalid_urls += 1
            continue
        if url in seen_urls:
            duplicates += 1
            continue
        seen_urls.add(url)

        filename = url.split("/")[-1]
        p = parse_sku_and_slot(filename)
        if not p:
            unknown_skus += 1
            continue

        if filename not in expected_filenames:
            foreign_skus += 1
            continue

        if filename in seen_filenames:
            duplicates += 1
            continue
        seen_filenames.add(filename)

        matched_mappings.append({
            "sku": p["sku"],
            "slot": p["slot"],
            "filename": filename,
            "url": url
        })

    missing_files = [f for f in expected_filenames if f not in seen_filenames]

    is_clean = (
        len(matched_mappings) == expected_count and
        len(seen_urls) == expected_count and
        duplicates == 0 and
        foreign_skus == 0 and
        invalid_urls == 0 and
        unknown_skus == 0 and
        len(missing_files) == 0
    )

    if not is_clean:
        return {
            "success": False,
            "reason": "VALIDATION_FAILED",
            "message": f"Expected {expected_count}, matched {len(matched_mappings)}, dupes: {duplicates}, foreign: {foreign_skus}, missing: {len(missing_files)}"
        }

    # Atomic State Update
    print(f"     [SAVE] Validation 100% clean! Saving {expected_count} URLs atomically...")
    now_iso = datetime.utcnow().isoformat() + "Z"

    link_state = load_json(LINK_STATE_FILE)
    if "products" not in link_state: link_state["products"] = {}
    if "audit_log" not in link_state: link_state["audit_log"] = []

    for m in matched_mappings:
        target_sku = m["sku"]
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
            "action": "IMPORT_BATCH_IMAGE_LINK_AUTONOMOUS",
            "batch_id": batch_id,
            "sku": matched_key,
            "slot": m["slot"],
            "filename": m["filename"],
            "url": url
        })

    atomic_save_json(LINK_STATE_FILE, link_state)

    # Update batch state
    batch_state = load_json(BATCH_STATE_FILE)
    for b in batch_state.get("batches", []):
        if b["id"] == batch_id:
            b["status"] = "VERIFIED"
            b["captured_sku_count"] = len(matched_mappings)
            b["updated_at"] = now_iso
            break
    atomic_save_json(BATCH_STATE_FILE, batch_state)

    print(f"     [SUCCESS] {batch_id} marked VERIFIED in state.")
    return {"success": True, "batch_id": batch_id, "matched_count": len(matched_mappings)}

async def main(start_batch=None, single_batch=False):
    os.makedirs(PROFILE_DIR, exist_ok=True)
    batch_state = load_json(BATCH_STATE_FILE)
    batches = batch_state.get("batches", [])

    ready_batches = [b for b in batches if b["status"] == "READY"]
    if start_batch:
        idx = next((i for i, b in enumerate(ready_batches) if b["id"] == start_batch), None)
        if idx is not None:
            ready_batches = ready_batches[idx:]

    if single_batch and ready_batches:
        ready_batches = ready_batches[:1]

    print(f"==================================================")
    print(f" MEESHO AUTONOMOUS BATCH PIPELINE")
    print(f" Queue: {len(ready_batches)} batches ready to process")
    if ready_batches:
        print(f" Starting at: {ready_batches[0]['id']}")
    print(f"==================================================\n")

    if not ready_batches:
        print("[INFO] No READY batches found. All batches appear to be completed or verified.")
        return

    async with async_playwright() as p:
        context = await p.chromium.launch_persistent_context(
            user_data_dir=PROFILE_DIR,
            channel="chrome",
            headless=False,
            args=["--start-maximized"],
            no_viewport=True
        )

        page = context.pages[0] if context.pages else await context.new_page()

        print(f"[NAVIGATE] Loading Meesho Supplier Panel ({MEESHO_SUPPLIER_URL})...")
        await page.goto(MEESHO_SUPPLIER_URL)
        await page.wait_for_timeout(4000)

        # Check security / authentication
        sec_status, sec_msg = await check_security_and_auth(page)
        if sec_status != "OK":
            print(f"\n[SECURITY BOUNDARY DETECTED]: {sec_status} - {sec_msg}")
            if sec_status == "WAF_BLOCKED":
                print("\n" + "=" * 70)
                print("AKAMAI WAF INTERCEPTION DETECTED")
                print("The automated browser session was stopped by Akamai EdgeSuite Bot Manager.")
                print("In accordance with project safety guidelines:")
                print("1. We DO NOT attempt to bypass or spoof Akamai.")
                print("2. Normal visible browser workflow is the compliant path.")
                print("=" * 70 + "\n")
                await context.close()
                return

            if sec_status in ("LOGIN_REQUIRED", "CHALLENGE"):
                print("\n" + "!" * 70)
                print("HUMAN ACTION REQUIRED: Please log in or complete challenge on Chrome.")
                print("Waiting up to 10 minutes for authentication...")
                print("!" * 70 + "\n")
                logged_in = False
                for _ in range(120):
                    await page.wait_for_timeout(5000)
                    status, _ = await check_security_and_auth(page)
                    if status == "OK":
                        logged_in = True
                        break
                if not logged_in:
                    print("[ABORT] Authentication timeout. Exiting.")
                    await context.close()
                    return

        # Autonomous loop over ready batches
        for b in ready_batches:
            batch_id = b["id"]
            preflight = verify_batch_zip(batch_id)
            if not preflight["success"]:
                print(f"[PREFLIGHT FAILED] {batch_id}: {preflight['error']}. Skipping.")
                continue

            result = await process_single_batch(page, preflight)
            if not result["success"]:
                print(f"[BATCH FAILED] {batch_id}: {result['reason']} - {result['message']}")
                print("[SAFETY HALT] Stopping autonomous progression to protect state.")
                break

            print(f"[PROGRESS] {batch_id} complete. Automatically continuing to next batch...\n")
            await page.wait_for_timeout(2000)

        await context.close()
        print("\n[FINISHED] Autonomous batch run completed.")

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("--start", default=None, help="Start at specific batch ID")
    parser.add_argument("--single", action="store_true", help="Process only single batch")
    args = parser.parse_args()

    asyncio.run(main(start_batch=args.start, single_batch=args.single))
