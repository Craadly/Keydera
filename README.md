# Keydera - PHP License and Updates Manager

A full‑featured PHP license and updates manager built with the CodeIgniter 3 framework. This repository currently includes a feature branch for a modern authentication UI redesign.

## Extended Description

Keydera provides everything you need to license, activate, and deliver software updates for your products:

- Issue and validate license keys, manage activations, and enforce limits per product/version.
- Serve updates and downloads securely, with analytics for visibility into usage.
- Integrate from your applications through a clean REST-style API (see `docs/API.md`).
- Admin UI for managing users, products, licenses, releases, and settings.

Architecture and stack:
- Backend: CodeIgniter 3 (MVC) with MySQL/MariaDB. Core app code under `application/` and `system/`.
- Controllers expose internal/external endpoints (e.g., `Api_internal`, `Api_external`).
- Views are PHP templates with vendor UI libraries (DataTables, Select2, CKEditor, Dropify, jQuery Validation, Font Awesome).
- Styles: project CSS under `assets/css/` (e.g., `premium-design.css`), with Tailwind/PostCSS configs available for utility workflows (`tailwind.config.js`, `postcss.config.js`).
 
- Internationalization: language packs in `application/language/`.

Feature branch – Authentication redesign:
- Two‑column split layout for login/forgot pages with a minimal auth wrapper (no sidebar/topbar during auth flows).
- Brand‑consistent purple gradient “branding” panel and responsive layout for all screen sizes.
- Modernized form controls: corrected logo (`logo-white.svg`), improved input padding/focus, aligned icons, consistent button styling.
- Footer refresh: edge‑to‑edge app footer with responsive layout and brand links.
- New/updated templates and styles: `application/views/users/login.php`, `application/views/users/forgot_password.php`, `application/views/templates/header_auth.php`, `application/views/templates/footer_auth.php`, and `assets/css/premium-design.css`.

Deployment & operations:
- Works with Apache/Nginx/PHP-FPM; sample Dockerfiles are provided under `docker/` and a `docker-compose.yml` for local runs.
- Keep credentials secure and use HTTPS in production.

Data model highlights:
- Users, Products, Licenses, Activations, Versions/Releases, and Downloads. See `docs/API.md` and the installer SQL under `install/` for schema details.

CI/CD and releases:
- GitHub Actions workflow under `.github/workflows/release.yml` can help with packaging and validating builds.

If you’re reviewing the feature branch, see the PR description for a concise change summary and screenshots.

## Docker (local dev + tests)

Prerequisites:
- Docker Desktop (Windows/macOS/Linux)

Quick start (Windows PowerShell):

```powershell
# Build and start web + db (and keep containers running)
docker compose up -d --build

# Follow Apache logs (optional)
docker compose logs -f web
```

Open http://localhost:8080

First run notes:
- Database is automatically seeded from `install/database.sql` via MySQL init (see `docker-compose.yml`).
- Inside containers, the DB host is `db` (service name). If you run the app on the host instead of in Docker, use host `127.0.0.1` and port `3307`.
- Set your base URL to `http://localhost:8080/` in `application/config/config.php` when using Docker.

Database configuration examples:

```php
// In Docker containers (web talks to db via service name)
$db['default'] = array(
   'hostname' => 'db',
   'username' => 'keydera_user',
   'password' => 'keydera_pass',
   'database' => 'keydera_db',
   'dbdriver' => 'mysqli',
);

// App on host talking to Docker MySQL published on 3307
$db['default'] = array(
   'hostname' => '127.0.0.1',
   'port'     => 3307,
   'username' => 'keydera_user',
   'password' => 'keydera_pass',
   'database' => 'keydera_db',
   'dbdriver' => 'mysqli',
);
```

Common commands:

```powershell
# Stop services
docker compose down

# Stop and remove volumes (resets MySQL data)
docker compose down -v

# Exec into the web container
docker compose exec web bash
```

 

Troubleshooting:
- If port 8080 is in use, change the host port mapping in `docker-compose.yml` (e.g., `8090:80`).
- XAMPP/MySQL conflict: the compose file publishes MySQL on `3307` to avoid clashes. Inside Docker use `db:3306`; from host use `127.0.0.1:3307`.
- If routing isn’t working, ensure `.htaccess` is present and that `AllowOverride All` is enabled (it is in the provided Apache vhost config).

## Features

- **🔐 License Management**: Generate, validate, and manage license keys with expiration dates
- **📱 Product Activation**: Control software activations with domain/IP restrictions  
- **🔄 Update Distribution**: Secure update delivery with version control and SQL migrations
- **🌐 REST API Integration**: 22 endpoints for client apps and server management
- **👥 User Management**: Multi-role authentication and user administration
- **📊 Analytics & Tracking**: Download statistics and activation monitoring
- **🌍 Multi-language Support**: English, Chinese, German, and Portuguese
- **📮 Postman Ready**: Complete API collection for instant testing
- **🐳 Docker Support**: Containerized development environment
- **🎨 Modern UI**: Responsive admin interface with authentication redesign

## Project Structure

```
keydera/
├── application/              # CodeIgniter application
│   ├── controllers/         # API and web controllers
│   │   ├── Api_external.php # Client-facing API endpoints
│   │   ├── Api_internal.php # Admin API endpoints  
│   │   ├── Users.php        # Authentication & user management
│   │   └── ...
│   ├── models/              # Database models
│   ├── views/               # PHP templates & UI
│   ├── config/              # Application configuration
│   └── language/            # Multi-language support
├── assets/                  # Frontend assets
│   ├── css/                # Custom stylesheets
│   ├── js/                 # JavaScript files
│   └── images/             # Image assets
├── docs/                   # Documentation
│   ├── API.md              # Complete API documentation
│   └── Keydera-API.postman_collection.json # Postman collection
├── docker/                 # Docker configuration
├── install/                # Installation files
│   ├── database.sql        # Database schema
│   └── install.php         # Web installer
└── system/                 # CodeIgniter framework
```

## Requirements

- **PHP**: 7.4 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache/Nginx with mod_rewrite enabled
- **Framework**: CodeIgniter 3.x (included)
- **Optional**: Docker Desktop for containerized development

## Installation

### Option 1: Traditional Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/Craadly/Keydera.git
   cd Keydera
   ```

2. **Configure web server**
   - Point document root to the project folder
   - Ensure mod_rewrite is enabled (Apache)

3. **Set up database**
   - Create a MySQL database
   - Import `install/database.sql`
   - Or use the web installer at `/install/`

4. **Configure application**
   - Copy `application/config/database.php.example` to `application/config/database.php`
   - Update database credentials
   - Set your base URL in `application/config/config.php`

### Option 2: Docker Setup (Recommended for Development)

1. **Prerequisites**: Docker Desktop installed

2. **Quick start**:
   ```bash
   git clone https://github.com/Craadly/Keydera.git
   cd Keydera
   docker compose up -d --build
   ```

3. **Access application**: http://localhost:8080

4. **Database auto-setup**: Automatically seeded from `install/database.sql`

## API Documentation

Keydera provides comprehensive REST APIs for both client applications and server-to-server management:

- **📖 Complete API Documentation**: See `docs/API.md` for detailed endpoint documentation with cURL examples
- **📮 Postman Collection**: Import `docs/Keydera-API.postman_collection.json` into Postman for instant API testing
- **🔌 External API**: 8 endpoints for client licensing, updates, and activations  
- **⚙️ Internal Admin API**: 14 endpoints for product and license management
- **🌐 Multi-language Support**: API responses in English, Chinese, German, and Portuguese
- **🔑 Authentication**: Uses LB-API-KEY headers with URL/IP validation

### Quick API Setup

1. **Import Postman Collection**:
   ```bash
   # Import the collection file into Postman
   docs/Keydera-API.postman_collection.json
   ```

2. **Configure API Variables**:
   - `base_url`: Your Keydera server URL
   - `external_api_key`: For client-facing operations  
   - `internal_api_key`: For admin operations
   - `client_url`, `client_ip`: Client installation details

3. **Test Connection**:
   ```bash
   POST /api/check_connection_ext  # External API
   POST /api/check_connection_int  # Internal Admin API
   ```

## Configuration

### Database Configuration
Edit `application/config/database.php`:

```php
$db['default'] = array(
   'hostname' => 'localhost',
   'username' => 'your_username', 
   'password' => 'your_password',
   'database' => 'your_database',
   'dbdriver' => 'mysqli',
   // ... other settings
);
```

### Base URL Configuration
Edit `application/config/config.php`:

```php
// For traditional setup
$config['base_url'] = 'https://your-domain.com/';

// For Docker development  
$config['base_url'] = 'http://localhost:8080/';
```

### API Configuration
Generate API keys in Settings → API Settings:
- **External API Key**: For client applications
- **Internal API Key**: For server-to-server operations

### API Integration Examples

**External API** (Client Applications):
- License activation and verification
- Software update checking and downloading
- Version management

**Internal Admin API** (Server Management):
- Product creation and management
- License generation and control
- Bulk license operations

## Getting Started

1. **Install and configure** using one of the methods above
2. **Access admin panel** at your-domain.com/admin (default: admin/admin)
3. **Generate API keys** in Settings → API Settings
4. **Import Postman collection** from `docs/Keydera-API.postman_collection.json`
5. **Test API endpoints** using the provided examples
6. **Integrate with your applications** using the REST API

## Security

- **🔑 Change default credentials**: Update admin/admin after installation
- **🔒 Use HTTPS in production**: Secure API communications
- **📱 API key management**: Regenerate keys regularly
- **🛡️ Database security**: Use strong credentials and restrict access
- **🔐 Environment separation**: Different keys for staging/production
- **📊 Monitor activations**: Watch for suspicious license usage

## License

This project is proprietary software owned by Craadly.

## Development

### Contributing
1. Fork the repository
2. Create a feature branch (`git checkout -b feat/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feat/amazing-feature`)
5. Open a Pull Request

### Testing API Changes
- Use the provided Postman collection for API testing
- Test both External and Internal API endpoints
- Verify multi-language responses (english, chinese, german, portuguese)

### Development Tools
- **API Documentation**: Always update `docs/API.md` when adding endpoints
- **Postman Collection**: Update collection when API changes
- **Docker**: Use `docker compose up -d` for consistent development environment

## Support

For support and questions:
- Email: sales@craadly.com
- GitHub Issues: [Create an issue](https://github.com/Craadly/keydera/issues)

## Version

Current version: 1.0.0

---
© 2025 Craadly. All rights reserved.

---

### Notes for the Auth Redesign Feature Branch

If you are working specifically on the authentication redesign:
- Primary files: `application/views/users/login.php`, `application/views/users/forgot_password.php`, `application/views/templates/header_auth.php`, `application/views/templates/footer_auth.php`, and `assets/css/premium-design.css`.
- The feature branch is `feat/auth-redesign`. A pull request is available for review at https://github.com/Craadly/Keydera/pull/3.
