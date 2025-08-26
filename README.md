# Keydera - Software Licensing and Update Manager

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/Craadly/Keydera/blob/main/LICENSE)
[![GitHub release (latest by date)](https://img.shields.io/github/v/release/Craadly/Keydera)](https://github.com/Craadly/Keydera/releases)
[![GitHub issues](https://img.shields.io/github/issues/Craadly/Keydera)](https://github.com/Craadly/Keydera/issues)

Keydera is a powerful, self-hosted software licensing and update manager built on the CodeIgniter 3 framework. It provides a complete, open-source solution for developers and software companies to manage product licenses, control access, and deliver updates to customers securely. It serves as a free alternative to commercial products like LicenseBox, giving you full control over your software distribution and licensing without any recurring fees.

---

## Core Features

*   **License Management:** Generate and manage license keys with support for different types and expiration dates. You can create licenses for single domains, multiple domains, or even unlimited domains. You can also set an expiration date for each license, making it easy to sell subscriptions or time-limited licenses.
*   **Product Updates:** A secure endpoint for your applications to check for new versions and download updates. This allows you to easily distribute new features, bug fixes, and security patches to your users.
*   **Activation Tracking:** Monitor where and when your software licenses are activated. This helps you prevent piracy and ensure that your customers are using your software in accordance with your licensing terms.
*   **Secure API:** Integrate with other services and applications through internal and external APIs. This allows you to automate your licensing and update processes, and to integrate Keydera with your existing systems.
*   **PHP Obfuscator:** A tool to help protect your PHP source code. This makes it more difficult for others to reverse-engineer your code and steal your intellectual property.

---

## Server Requirements

Before you begin, ensure your server meets the following requirements:

*   PHP version 8.1 or newer
*   MySQL 5.7+ or MariaDB 10.4+
*   `mod_rewrite` Apache module enabled (or equivalent for other web servers)
*   PHP `curl` and `zip` extensions
*   The `application/config/` and `application/cache/` directories must be writable.

---

## Installation

1.  **Download:** Get the latest release from the [GitHub Releases](https://github.com/Craadly/Keydera/releases) page or clone the repository.
    ```bash
    git clone https://github.com/Craadly/Keydera.git
    ```
2.  **Database Setup:**
    *   Create a new database in MySQL or MariaDB.
    *   Import the `install/database.sql` schema into your new database.
3.  **Configuration:**
    *   Rename `application/config/database.php.example` to `database.php` and fill in your database credentials.
    *   Rename `application/config/config.php.example` to `config.php` and configure your `base_url`.
4.  **Install:** Navigate to the `/install` directory in your browser. The installer will guide you through the final steps and set up your admin account.
    `http://your-domain.com/keydera/install/`
5.  **Secure:** For security, **delete the `install/` directory** after installation is complete.

---

## Security Best Practices

To ensure your Keydera installation is secure, please follow these recommendations:

*   **Delete the Install Directory:** After installation, immediately delete the `install/` directory from your server.
*   **Enforce HTTPS:** Always use a valid SSL certificate and enforce HTTPS to protect data in transit. Configure your web server to redirect all HTTP traffic to HTTPS.
*   **Set `ENVIRONMENT` to Production:** In the main `index.php` file, make sure the `ENVIRONMENT` constant is set to `production` to disable verbose error reporting.
*   **Use Strong API Keys:** When configuring API access, use long, randomly generated strings for your keys and restrict their usage where possible.
*   **Keep Dependencies Updated:** Regularly check for updates to Keydera and its dependencies (like CodeIgniter and other libraries) to patch any security vulnerabilities.
*   **Restrict File Permissions:** Ensure that file permissions are set as restrictively as possible. Core application files should not be writable by the web server user.

---

## Quick Start

### Creating Your First Product
1.  Log into the admin dashboard.
2.  Navigate to **Products** > **Add New**.
3.  Enter product details (name, version, description).
4.  Set licensing parameters (e.g., expiration, domain limits).
5.  Upload your PHP files for obfuscation (optional).

### Generating Integration Code
1.  Go to the **Generate Helpers** section.
2.  Select your target platform (e.g., PHP, WordPress).
3.  Download the generated helper files and integrate them into your application.

### Managing Licenses
1.  Navigate to the **Licenses** section.
2.  Create new licenses for your products.
3.  Monitor activations and usage statistics.

---

## Integration Examples

Sample integration code can be found in the `application/helpers/integrations/` directory.

### Generic PHP Application
```php
require_once 'keydera_helper.php';

$license = new KeyderaLicense('YOUR_PRODUCT_ID', 'CUSTOMER_LICENSE_KEY');
if ($license->validate()) {
    // Your protected code here
    echo "License is valid. Full functionality enabled.";
} else {
    echo "Invalid license. Please contact support.";
}
```

---

## API Reference

A detailed API reference is available in `/docs/API.md`.

---

## Configuration

Key configuration files are located in `application/config/`:
-   `config.php`: General application settings.
-   `database.php`: Database connection settings.
-   `lb_config.php`: Core license and product settings.

You can configure most settings from the admin dashboard under **Settings**.

---

## Troubleshooting

### Common Issues
*   **License validation fails:** Check server connectivity, verify API endpoints are accessible, and ensure a valid SSL/TLS certificate is installed.
*   **Updates not working:** Confirm download permissions, check file system write access, and verify update server connectivity.
*   **Performance issues:** Enable caching in your configuration, optimize database queries, and consider server resource allocation.

### Debug Mode
To get detailed error logs, you can enable debug mode by setting `ENVIRONMENT` to `development` in the main `index.php` file. **Do not use debug mode in a production environment.**

---

## Support

*   **Documentation**: This `README.md` file and the `/docs` directory.
*   **API Examples**: Sample implementations in `application/helpers/integrations/`.
*   **Issues**: Report bugs or request features on the [GitHub Issues](https://github.com/Craadly/Keydera/issues) page.
