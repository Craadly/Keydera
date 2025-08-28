import { test, expect } from '@playwright/test';

test.describe('Left Navigation Bar Redesign', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to the login page which shows the navigation
    await page.goto('http://web/users/login');
  });

  test('should display left sidebar navigation', async ({ page }) => {
    // Check if sidebar exists
    const sidebar = page.locator('.is-sidebar');
    await expect(sidebar).toBeVisible();

    // Check if sidebar has logo
    const logo = sidebar.locator('.sidebar-logo');
    await expect(logo).toBeVisible();

    // Check main navigation items
    await expect(sidebar.locator('text=Dashboard')).toBeVisible();
    await expect(sidebar.locator('text=Products')).toBeVisible();
    await expect(sidebar.locator('text=Licenses')).toBeVisible();
    await expect(sidebar.locator('text=Activations')).toBeVisible();
    await expect(sidebar.locator('text=Downloads')).toBeVisible();
  });

  test('should display top navigation bar', async ({ page }) => {
    // Check if top bar exists
    const topBar = page.locator('.top-bar');
    await expect(topBar).toBeVisible();

    // Check breadcrumbs
    const breadcrumbs = topBar.locator('.breadcrumb');
    await expect(breadcrumbs).toBeVisible();

    // Check user menu
    const userMenu = topBar.locator('.navbar-dropdown');
    await expect(userMenu).toBeHidden(); // Hidden by default

    // Click user menu to open it
    await topBar.locator('.navbar-link').last().click();
    await expect(userMenu.last()).toBeVisible();
  });

  test('should toggle sidebar on desktop', async ({ page }) => {
    // Set desktop viewport
    await page.setViewportSize({ width: 1200, height: 800 });

    const sidebar = page.locator('.is-sidebar');
    const toggleButton = page.locator('.sidebar-toggle-desktop');

    // Initially sidebar should be expanded
    await expect(sidebar).not.toHaveClass(/is-collapsed/);

    // Click toggle button
    await toggleButton.click();

    // Sidebar should now be collapsed
    await expect(sidebar).toHaveClass(/is-collapsed/);

    // Click again to expand
    await toggleButton.click();
    await expect(sidebar).not.toHaveClass(/is-collapsed/);
  });

  test('should handle mobile navigation', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });

    const sidebar = page.locator('.is-sidebar');
    const mobileToggle = page.locator('.sidebar-toggle-mobile');

    // Initially sidebar should be hidden on mobile
    await expect(sidebar).not.toHaveClass(/is-active/);

    // Click mobile toggle
    await mobileToggle.click();

    // Sidebar should be visible
    await expect(sidebar).toHaveClass(/is-active/);

    // Click outside to close
    await page.locator('.main-content').click();
    await expect(sidebar).not.toHaveClass(/is-active/);
  });

  test('should have proper accessibility attributes', async ({ page }) => {
    const sidebar = page.locator('.is-sidebar');
    const toggleDesktop = page.locator('.sidebar-toggle-desktop');
    const toggleMobile = page.locator('.sidebar-toggle-mobile');

    // Check ARIA labels
    await expect(sidebar).toHaveAttribute('aria-label', 'Main Navigation');
    await expect(toggleDesktop).toHaveAttribute('aria-label', 'Toggle Sidebar');
    await expect(toggleMobile).toHaveAttribute('aria-label', 'Toggle Sidebar');

    // Check navigation menu structure
    const menuItems = sidebar.locator('.menu-list a');
    const firstMenuItem = menuItems.first();
    await expect(firstMenuItem).toHaveAttribute('aria-label');

    // Check focus management
    await toggleDesktop.focus();
    await expect(toggleDesktop).toBeFocused();

    // Test keyboard navigation
    await page.keyboard.press('Enter');
    // Should toggle sidebar
    await expect(sidebar).toHaveClass(/is-collapsed/);
  });

  test('should display correct breadcrumbs', async ({ page }) => {
    const currentBreadcrumb = page.locator('.breadcrumb .breadcrumb-current');

    // Should show Dashboard as current page
    await expect(currentBreadcrumb).toContainText('Dashboard');

    // Navigate to products page and check breadcrumbs
    await page.click('text=Products');
    await page.waitForURL('**/products');

    const productsCurrent = page.locator('.breadcrumb .breadcrumb-current');
    await expect(productsCurrent).toContainText('Products');
  });

  test('should be responsive across different screen sizes', async ({ page }) => {
    // Test tablet view
    await page.setViewportSize({ width: 768, height: 1024 });
    
    const sidebar = page.locator('.is-sidebar');
    const mainContent = page.locator('.main-content');
    
    await expect(sidebar).toBeVisible();
    await expect(mainContent).toBeVisible();

    // Test desktop view
    await page.setViewportSize({ width: 1440, height: 900 });
    
    await expect(sidebar).toBeVisible();
    await expect(mainContent).toBeVisible();

    // Test mobile view
    await page.setViewportSize({ width: 320, height: 568 });
    
    const mobileToggle = page.locator('.sidebar-toggle-mobile');
    await expect(mobileToggle).toBeVisible();
  });
});
