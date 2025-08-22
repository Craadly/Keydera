# Keydera - Software Licensing and Update Manager

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/Craadly/Keydera/blob/main/LICENSE)
[![GitHub release (latest by date)](https://img.shields.io/github/v/release/Craadly/Keydera)](https://github.com/Craadly/Keydera/releases)
[![GitHub issues](https://img.shields.io/github/issues/Craadly/Keydera)](https://github.com/Craadly/Keydera/issues)

Keydera is a powerful, self-hosted software licensing and update manager built on the CodeIgniter 3 framework. It provides a complete, open-source solution for developers and software companies to manage product licenses, control access, and deliver updates to customers securely. It serves as a free alternative to commercial products like LicenseBox, giving you full control over your software distribution and licensing without any recurring fees.

---


## Core Features

* **License Management:** Generate and manage license keys with support for different types and expiration dates. You can create licenses for single domains, multiple domains, or even unlimited domains. You can also set an expiration date for each license, making it easy to sell subscriptions or time-limited licenses.

* **Product Updates:** A secure endpoint for your applications to check for new versions and download updates. This allows you to easily distribute new features, bug fixes, and security patches to your users.

* **Activation Tracking:** Monitor where and when your software licenses are activated. This helps you prevent piracy and ensure that your customers are using your software in accordance with your licensing terms.

* **Secure API:** Integrate with other services and applications through internal and external APIs. This allows you to automate your licensing and update processes, and to integrate Keydera with your existing systems.

* **PHP Obfuscator:** A tool to help protect your PHP source code. This makes it more difficult for others to reverse-engineer your code and steal your intellectual property.

---

## Server Requirements

Before you begin, ensure your server meets the following requirements:

* PHP version 5.6 or newer (7.4+ recommended)
* MySQL 5.1+ or MariaDB
* `mod_rewrite` Apache module enabled
* PHP `curl` and `zip` extensions
* The `application/config/` directory must be writable (`chmod 755` or `775`).

---

## Installation

1.  **Download:** Get the latest release from the [GitHub Releases](https://github.com/Craadly/Keydera/releases) page or clone the repository.

    ```bash
    git clone [https://github.com/Craadly/Keydera.git](https://github.com/Craadly/Keydera.git)
    ```

2.  **Install:** Navigate to the `/install` directory in your browser. The installer will guide you through the setup process, including database configuration.

    `http://your-domain.com/keydera/install/`

3.  **Secure:** For security, **delete the `install/` directory** after installation is complete.

---

## Features

### License Management
- **Flexible licensing** - Create time-based, domain-restricted, or feature-limited licenses
- **Activation tracking** - Monitor where and when your software is activated
- **Remote license verification** - Real-time validation against your server
- **License revocation** - Instantly disable compromised or expired licenses

### Auto-Updates
- **Seamless updates** - Push updates directly to licensed installations
- **Version control** - Manage multiple versions and rollback capabilities
- **Selective distribution** - Control which licenses receive specific updates
- **Update notifications** - Keep users informed of available updates

### PHP Obfuscation
- **Code protection** - Built-in PHP obfuscation to protect your source code
- **Performance optimized** - Minimal impact on execution speed
- **Selective encoding** - Choose which files to obfuscate
- **Compatibility maintained** - Works with existing PHP frameworks

### Developer Tools
- **Helper generators** - Automatically create integration code for your projects
- **API endpoints** - RESTful APIs for external integrations
- **Sample implementations** - Ready-to-use examples for common scenarios
- **Multi-language support** - Interface available in multiple languages

---

## Installation

### 1. Server Setup
Ensure your server meets the requirements:
- PHP 8.1+ with required extensions
- MySQL 5.7+ or MariaDB 10.4+
- Web server (Apache/Nginx) with HTTPS
- Writable directories for cache and logs

### 2. Download and Extract
```bash
# Extract Keydera files to your web directory
unzip keydera.zip -d /var/www/html/keydera/
```

### 3. Database Configuration
1. Create a MySQL database for Keydera
2. Import the database schema: `install/database.sql`
3. Configure database settings in `application/config/database.php`

### 4. Web Installation
1. Navigate to `http://your-domain.com/keydera/install/`
2. Follow the installation wizard
3. Set up your admin account
4. Configure basic settings

---

## Quick Start
<<<<<<< HEAD

### Creating Your First Product
1. Log into the admin dashboard
2. Navigate to **Products** > **Add New**
3. Enter product details (name, version, description)
4. Set licensing parameters
5. Upload your PHP files for obfuscation (optional)

### Generating Integration Code
1. Go to **Generate Helpers** section
2. Select your target platform (PHP, WordPress, OpenCart)
3. Configure API endpoints and validation rules
4. Download the generated helper files
5. Integrate into your application

### Managing Licenses
1. Navigate to **Licenses** section
2. Create new licenses for your products
3. Set expiration dates, domain restrictions, or feature limits
4. Monitor activations and usage statistics

---

## Integration Examples

### PHP Application
```php
require_once 'keydera_helper.php';

$license = new KeyderaLicense('your-product-key');
if ($license->validate()) {
    // Your protected code here
    echo "License valid - full functionality enabled";
} else {
    echo "Invalid license - please contact support";
}
```

### WordPress Plugin
```php
// In your main plugin file
if (!class_exists('KeyderaWP')) {
    require_once plugin_dir_path(__FILE__) . 'includes/keydera-wp.php';
}

$keydera = new KeyderaWP('your-product-slug');
if ($keydera->is_valid()) {
    // Plugin functionality
}
```

---

## API Reference

### License Verification
```
POST /api/verify-license
```
**Parameters:**
- `product_key` - Your product identifier
- `license_key` - Customer's license key  
- `domain` - Customer's domain (for domain-locked licenses)

**Response:**
```json
{
    "status": "valid",
    "expires": "2024-12-31",
    "features": ["feature1", "feature2"]
}
```

### Update Check
```
GET /api/check-updates/{product_key}/{current_version}
```

### Download Update
```
GET /api/download-update/{product_key}/{license_key}
```

---

## Configuration

### Environment Settings
Key configuration files:
- `application/config/config.php` - General application settings
- `application/config/database.php` - Database connection
- `application/config/lb_config.php` - License server configuration

### Email Configuration
Configure SMTP settings in `Settings > Email Settings`:
- SMTP host and port
- Authentication credentials
- Email templates for notifications

### Security Settings
- Enable/disable features per user role
- Set API rate limits
- Configure encryption keys
- Manage trusted domains

---

## Troubleshooting

### Common Issues

**License validation fails**
- Check server connectivity
- Verify API endpoints are accessible
- Ensure proper SSL/TLS configuration

**Updates not working**
- Confirm download permissions
- Check file system write access
- Verify update server connectivity

**Performance issues**
- Enable caching in configuration
- Optimize database queries
- Consider server resource allocation

### Debug Mode
Enable debug mode by setting `ENVIRONMENT = 'development'` in `index.php` for detailed error logging.

---

## Support

- **Documentation**: Full documentation available in the `/docs` directory
- **API Examples**: Sample implementations in `application/libraries/api_helper_samples/`
- **Community**: Visit our support forums
- **Commercial Support**: Available for enterprise customers
=======

### Creating Your First Product
1. Log into the admin dashboard
2. Navigate to **Products** > **Add New**
3. Enter product details (name, version, description)
4. Set licensing parameters
5. Upload your PHP files for obfuscation (optional)

### Generating Integration Code
1. Go to **Generate Helpers** section
2. Select your target platform (PHP, WordPress, OpenCart)
3. Configure API endpoints and validation rules
4. Download the generated helper files
5. Integrate into your application

### Managing Licenses
1. Navigate to **Licenses** section
2. Create new licenses for your products
3. Set expiration dates, domain restrictions, or feature limits
4. Monitor activations and usage statistics

---

## Integration Examples

### PHP Application
```php
require_once 'keydera_helper.php';

$license = new KeyderaLicense('your-product-key');
if ($license->validate()) {
    // Your protected code here
    echo "License valid - full functionality enabled";
} else {
    echo "Invalid license - please contact support";
}
```

### WordPress Plugin
```php
// In your main plugin file
if (!class_exists('KeyderaWP')) {
    require_once plugin_dir_path(__FILE__) . 'includes/keydera-wp.php';
}

$keydera = new KeyderaWP('your-product-slug');
if ($keydera->is_valid()) {
    // Plugin functionality
}
```

---

## API Reference

### License Verification
```
POST /api/verify-license
```
**Parameters:**
- `product_key` - Your product identifier
- `license_key` - Customer's license key  
- `domain` - Customer's domain (for domain-locked licenses)

**Response:**
```json
{
    "status": "valid",
    "expires": "2024-12-31",
    "features": ["feature1", "feature2"]
}
```

### Update Check
```
GET /api/check-updates/{product_key}/{current_version}
```

### Download Update
```
GET /api/download-update/{product_key}/{license_key}
```

---

## Configuration

### Environment Settings
Key configuration files:
- `application/config/config.php` - General application settings
- `application/config/database.php` - Database connection
- `application/config/lb_config.php` - License server configuration

### Email Configuration
Configure SMTP settings in `Settings > Email Settings`:
- SMTP host and port
- Authentication credentials
- Email templates for notifications

### Security Settings
- Enable/disable features per user role
- Set API rate limits
- Configure encryption keys
- Manage trusted domains

---

## Troubleshooting

### Common Issues

**License validation fails**
- Check server connectivity
- Verify API endpoints are accessible
- Ensure proper SSL/TLS configuration

**Updates not working**
- Confirm download permissions
- Check file system write access
- Verify update server connectivity

**Performance issues**
- Enable caching in configuration
- Optimize database queries
- Consider server resource allocation

### Debug Mode
Enable debug mode by setting `ENVIRONMENT = 'development'` in `index.php` for detailed error logging.

---

## Support

- **Documentation**: Full documentation available in the `/docs` directory
- **API Examples**: Sample implementations in `application/libraries/api_helper_samples/`
- **Community**: Visit our support forums
- **Commercial Support**: Available for enterprise customers

