# Keydera

**Unified licensing, auto-updates, and PHP obfuscation** for **PHP apps**, **WordPress plugins/themes**, and **OpenCart extensions**. Easy to integrate with **sample code** and a **helper file generator**.

---

## Why Keydera?

- Clean, **developer-friendly** admin—get in, ship, repeat.  


- Runs on **minimal server resources** (shared/VPS friendly).  
- **Built-in PHP obfuscation/encoding** to protect distributed code.  
- **Examples + helper generator** for fast integration.  


---

## Supported targets

- **PHP** libraries/apps  
- **WordPress** plugins & themes  
- **OpenCart** extensions (3.x/4.x)

---

## Requirements (server)

- PHP **8.1+** with `curl`, `openssl`, `json`, `mbstring`, `pdo_mysql`  
- MySQL **5.7+** / MariaDB **10.4+**, HTTPS (Nginx/Apache)

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

---

## License

This software is proprietary. Please see `license.txt` for full terms and conditions.

---
