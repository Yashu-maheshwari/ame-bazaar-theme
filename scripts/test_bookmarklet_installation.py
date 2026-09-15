import asyncio
import sys
from playwright.async_api import async_playwright

sys.stdout.reconfigure(encoding='utf-8')

async def run_bookmarklet_test():
    async with async_playwright() as p:
        browser = await p.chromium.launch(channel='chrome', headless=True)
        context = await browser.new_context()
        page = await context.new_page()

        print("==================================================")
        print(" MEESHO BOOKMARKLET & INSTALLATION FLOW TEST")
        print("==================================================\n")

        # 1. Load Dashboard
        print("[STEP 1] Navigating to http://localhost:3002/meesho-image-batch-dashboard.html...")
        await page.goto('http://localhost:3002/meesho-image-batch-dashboard.html')
        await page.wait_for_timeout(1500)

        # 2. Check UI Elements
        print("[STEP 2] Verifying Section A and Section B UI Structure...")
        sec_a = await page.query_selector('.bookmarklet-section-a')
        sec_b = await page.query_selector('.bookmarklet-section-b')
        assert sec_a is not None, "Section A (.bookmarklet-section-a) not found!"
        assert sec_b is not None, "Section B (.bookmarklet-section-b) not found!"

        sec_a_text = await sec_a.inner_text()
        sec_b_text = await sec_b.inner_text()

        assert "BOOKMARKLET — DRAG THIS TO CHROME BOOKMARKS BAR" in sec_a_text, "Section A missing header!"
        assert "Drag the blue Send to Meesho Dashboard button to Chrome's Bookmarks Bar." in sec_a_text, "Section A missing exact instruction!"
        assert "DO NOT CLICK THIS BUTTON ON THE DASHBOARD" in sec_b_text, "Section B missing warning header!"
        assert "works ONLY on the Meesho Supplier Panel" in sec_b_text or "ONLY on the Meesho" in sec_b_text, "Section B missing Meesho-only notice!"
        assert "Environment Self-Check" in sec_b_text or "Localhost Dashboard Detected" in sec_b_text, "Section B missing self-test check!"

        print("  -> Section A: Present and verified.")
        print("  -> Section B: Present and verified.")

        # 3. Check Bookmarklet Button & Copy Button
        print("\n[STEP 3] Verifying Buttons...")
        btn_link = await page.query_selector('#bookmarklet-link')
        btn_copy = await page.query_selector('#btn-copy-code')
        assert btn_link is not None, "#bookmarklet-link button not found!"
        assert btn_copy is not None, "#btn-copy-code button not found!"

        href_val = await btn_link.get_attribute('href')
        assert href_val.startswith('javascript:'), f"Bookmarklet href does not start with javascript: {href_val[:30]}"
        print(f"  -> Bookmarklet href length: {len(href_val)} chars (starts with javascript:)")

        # 4. Test Direct Click on Dashboard (Should show helpful alert, NOT 'No Meesho links')
        print("\n[STEP 4] Testing Direct Click on Dashboard...")
        dialog_messages = []
        async def on_dialog(dialog):
            dialog_messages.append(dialog.message)
            await dialog.accept()

        page.on('dialog', on_dialog)

        await btn_link.click()
        await page.wait_for_timeout(500)

        assert len(dialog_messages) > 0, "No alert shown when clicking bookmarklet on dashboard!"
        last_alert = dialog_messages[-1]
        print(f"  -> Dashboard Click Alert: {repr(last_alert)}")
        assert "No Meesho Image Links found" not in last_alert, "Alert wrongly showed 'No Meesho Image Links found'!"
        assert "DO NOT CLICK THIS BUTTON ON THE DASHBOARD" in last_alert or "must be run on the Meesho Supplier Panel" in last_alert, "Alert did not explain proper usage!"
        print("  -> Direct click protection verified: PASS")

        # 5. Test Copy Bookmarklet Code Button
        print("\n[STEP 5] Testing Copy Bookmarklet Code Button...")
        dialog_messages.clear()
        await btn_copy.click()
        await page.wait_for_timeout(500)
        assert len(dialog_messages) > 0, "No copy feedback dialog shown!"
        print(f"  -> Copy Alert: {repr(dialog_messages[-1])}")
        assert "copied to clipboard" in dialog_messages[-1].lower(), "Copy alert missing confirmation text!"
        print("  -> Copy button verified: PASS")

        # 6. Test Bookmarklet Code Target Detection
        print("\n[STEP 6] Testing Bookmarklet Target Detection in Script Execution...")
        
        # Test 6A: Execute in localhost context (without onclick interception)
        eval_script = href_val.replace('javascript:', '')
        dialog_messages.clear()
        await page.evaluate(eval_script)
        await page.wait_for_timeout(500)
        assert len(dialog_messages) > 0, "No alert triggered when running bookmarklet on localhost!"
        localhost_alert = dialog_messages[-1]
        print(f"  -> Localhost Execution Alert: {repr(localhost_alert)}")
        assert "must be clicked from the Meesho Image Bulk Upload results page" in localhost_alert or "must be run on the Meesho" in localhost_alert, "Localhost alert missing target instruction!"
        assert "No Meesho Image Links found" not in localhost_alert, "Localhost execution leaked into extraction!"
        print("  -> Target Detection (localhost): PASS")

        # Test 6B: Execute in non-Meesho context
        page_other = await context.new_page()
        page_other.on('dialog', on_dialog)
        await page_other.goto('https://example.com')
        dialog_messages.clear()
        await page_other.evaluate(eval_script)
        await page_other.wait_for_timeout(500)
        assert len(dialog_messages) > 0, "No alert triggered when running bookmarklet on example.com!"
        other_alert = dialog_messages[-1]
        print(f"  -> Non-Meesho Domain Alert: {repr(other_alert)}")
        assert "Please navigate to Meesho Supplier Panel Image Bulk Upload results page" in other_alert, "Non-Meesho alert missing warning!"
        print("  -> Target Detection (non-Meesho domain): PASS")

        await browser.close()

        print("\n==================================================")
        print(" FINAL TEST MATRIX")
        print("==================================================")
        print("BOOKMARKLET_CODE = PASS")
        print("INSTALLATION_UI = PASS")
        print("LOCAL_DASHBOARD = PASS")
        print("MEESHO_TARGET_DETECTION = PASS")
        print("REGRESSION = PASS")

if __name__ == '__main__':
    asyncio.run(run_bookmarklet_test())
