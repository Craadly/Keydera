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
- Testing: Playwright UI tests live in `tests/ui/` with Docker support under `docker/playwright/`.
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

## Features

- License key generation and validation
- Product activation management  
- Update distribution system
- API integration for external applications
- User management and authentication
- Download tracking and analytics
- Multi-language support

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- Apache/Nginx web server
- CodeIgniter 3.x framework (included)

## Installation

1. **Clone the repository**
   
   ```bash
   git clone https://github.com/Craadly/Keydera.git
   cd Keydera
   ```

2. **Configure your web server**
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

## Configuration

### Database Configuration
Edit `application/config/database.php`:

```php
$db['default'] = array(
   'hostname' => 'localhost',
   'username' => 'your_username', 
   'password' => 'your_password',
   'database' => 'your_database',
   // ... other settings
);
```

### Base URL
Edit `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/keydera-clean/';
// or
$config['base_url'] = 'https://localhost/keydera-clean/';
```

## API Documentation

See `docs/API.md` for detailed API documentation and integration examples.

## Security

- Change default admin credentials after installation
- Use HTTPS in production
- Keep the application updated
- Secure your database credentials

## License

This project is licensed under the CodeCanyon Standard License.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch  
5. Create a Pull Request

## Support

For support and questions:
- Email: tagkais@gmail.com
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
