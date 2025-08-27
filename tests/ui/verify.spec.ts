import { test } from '@playwright/test';

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080';

async function maybeToggleSidebar(page) {
  const selectors = [
    '[data-testid="sidebar-toggle"]',
    'button[aria-label*="collapse" i]',
    'button[aria-label*="toggle" i]',
    '.sidebar-toggle-desktop',
    '#sidebar-toggle'
  ];
  for (const sel of selectors) {
    const btn = page.locator(sel);
    if (await btn.first().isVisible().catch(() => false)) {
      await btn.first().click({ trial: false }).catch(() => {});
      await page.waitForTimeout(400);
      return true;
    }
  }
  return false;
}

async function capture(page, name) {
  await page.screenshot({ path: `tests/ui/screenshots/${name}.png`, fullPage: true });
}

// Desktop
test('desktop: expanded and collapsed + basic nav', async ({ browser }) => {
  const context = await browser.newContext({ viewport: { width: 1366, height: 900 } });
  const page = await context.newPage();
  await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

  await capture(page, 'desktop-expanded');

  await maybeToggleSidebar(page);
  await capture(page, 'desktop-collapsed');

  await context.close();
});

// Tablet
test('tablet: expanded and collapsed', async ({ browser }) => {
  const context = await browser.newContext({ viewport: { width: 1024, height: 800 } });
  const page = await context.newPage();
  await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

  await capture(page, 'tablet-expanded');

  await maybeToggleSidebar(page);
  await capture(page, 'tablet-collapsed');

  await context.close();
});

// Mobile
test('mobile: overlay behavior', async ({ browser }) => {
  const context = await browser.newContext({ viewport: { width: 375, height: 812 }, isMobile: true, deviceScaleFactor: 2 });
  const page = await context.newPage();
  await page.goto(BASE_URL, { waitUntil: 'domcontentloaded' });

  // Try to open the mobile overlay menu (hamburger)
  const hamburgerSelectors = [
    '[data-testid="mobile-menu"]',
    'button[aria-label*="menu" i]',
    '.sidebar-toggle-mobile',
    '.hamburger',
    '#hamburger'
  ];
  for (const sel of hamburgerSelectors) {
    const btn = page.locator(sel);
    if (await btn.first().isVisible().catch(() => false)) {
      await btn.first().click().catch(() => {});
      await page.waitForTimeout(400);
      break;
    }
  }

  await capture(page, 'mobile-overlay');

  await context.close();
});
