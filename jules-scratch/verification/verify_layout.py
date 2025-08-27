import re
from playwright.sync_api import Page, expect, sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # 1. Navigate to the login page
    page.goto("http://localhost:8000/users/login")

    # 2. Log in
    page.get_by_placeholder("Enter your username").fill("Craadly")
    page.get_by_placeholder("Enter your password").fill("Keydera.com")
    page.get_by_role("button", name="Login").click()

    # 3. Wait for dashboard and take screenshot
    expect(page).to_have_url(re.compile(r".*/$"))
    expect(page.locator(".is-sidebar")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/01_dashboard_expanded.png")

    # 4. Collapse sidebar and take another screenshot
    page.locator(".sidebar-toggle-desktop").click()
    expect(page.locator(".is-sidebar")).to_have_class(re.compile(r"is-collapsed"))
    page.screenshot(path="jules-scratch/verification/02_dashboard_collapsed.png")

    # 5. Expand sidebar and take another screenshot
    page.locator(".sidebar-toggle-desktop").click()
    expect(page.locator(".is-sidebar:not(.is-collapsed)")).to_be_visible()
    page.screenshot(path="jules-scratch/verification/03_dashboard_expanded_again.png")

    context.close()
    browser.close()

with sync_playwright() as playwright:
    run(playwright)
