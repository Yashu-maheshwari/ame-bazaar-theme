import asyncio
import sys
from playwright.async_api import async_playwright

# Set stdout to UTF-8
sys.stdout.reconfigure(encoding='utf-8')

async def test_button():
    async with async_playwright() as p:
        browser = await p.chromium.launch(channel='chrome', headless=True)
        page = await browser.new_page()

        api_called = False
        post_body = None

        async def handle_request(route, request):
            nonlocal api_called, post_body
            if '/api/open-zip-folder' in request.url:
                api_called = True
                post_body = request.post_data
                print(f"Captured request to /api/open-zip-folder: {post_body}")
            await route.continue_()

        await page.route('**/api/open-zip-folder', handle_request)

        print("Navigating to http://localhost:3002/meesho-image-batch-dashboard.html...")
        await page.goto('http://localhost:3002/meesho-image-batch-dashboard.html')
        await page.wait_for_timeout(2000)

        btn = await page.query_selector('#btn-reveal-zip')
        assert btn is not None, "Button #btn-reveal-zip not found"
        initial_text = await btn.inner_text()
        print(f"Initial button text: {repr(initial_text)}")

        print("Clicking #btn-reveal-zip button...")
        await btn.click()
        await page.wait_for_timeout(1000)

        btn_text_after = await btn.inner_text()
        print(f"Button text after click: {repr(btn_text_after)}")
        print(f"API called: {api_called}")

        assert api_called, "API /api/open-zip-folder was not called!"
        assert "Opened in Explorer" in btn_text_after, f"Button text did not update: {btn_text_after}"
        print("BUTTON_E2E_TEST = PASS")
        await browser.close()

if __name__ == '__main__':
    asyncio.run(test_button())
