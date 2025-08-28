# Keydera - Auth Redesign Feature Branch

This branch contains a complete redesign of the authentication experience with modern UI components and improved user experience.

## 🚀 New Features in This Branch

### Authentication Layout Redesign
- **Two-column split layout** for login and forgot password pages
- **Minimal auth wrapper** (no sidebar/topbar during auth flows)
- **Brand-consistent purple gradient** branding panel
- **Responsive design** that adapts to mobile screens

### UI/UX Improvements
- **Fixed logo references** - now uses correct `logo-white.svg`
- **Polished form inputs** with proper padding, focus states, and icon alignment
- **Modern button styling** with hover effects and consistent sizing
- **Improved spacing** throughout auth forms
- **Clean notification styling** for success/error messages

### Footer Enhancement
- **Modern app footer** with brand links and version display
- **Edge-to-edge design** (removed max-width constraint)
- **Responsive layout** for mobile devices

## 🚀 GitHub Setup Instructions

**To push this branch to GitHub:**

1. **Create a new repository** on GitHub.com
2. **Copy the repository URL** (HTTPS or SSH)
3. **Run these commands:**

```bash
# Add your GitHub repo as origin
git remote add origin YOUR_GITHUB_REPO_URL

# Push this feature branch
git push -u origin feat/auth-redesign
```

---

## Original Keydera Features

- License key generation and validation
- Product activation management  
- Update distribution system
- API integration for external applications
- User management and authentication
- Download tracking and analytics
- Multi-language support

##  Requirements

- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- Apache/Nginx web server
- CodeIgniter 3.x framework (included)

##  Installation

1. **Clone the repository**
   `ash
   git clone https://github.com/Craadly/keydera.git
   cd keydera
   `

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

##  Configuration

### Database Configuration
Edit `application/config/database.php`:
`php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'your_username', 
    'password' => 'your_password',
    'database' => 'your_database',
    // ... other settings
);
`

### Base URL
Edit `application/config/config.php`:
`php
$config['base_url'] = 'https://localhost/keydera-clean/ (or http://localhost/keydera-clean/)';
`

##  API Documentation

See `docs/API.md` for detailed API documentation and integration examples.

##  Security

- Change default admin credentials after installation
- Use HTTPS in production
- Keep the application updated
- Secure your database credentials

##  License

This project is licensed under the CodeCanyon Standard License.

##  Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch  
5. Create a Pull Request

##  Support

For support and questions:
- Email: tagkais@gmail.com
- GitHub Issues: [Create an issue](https://github.com/Craadly/keydera/issues)

##  Version

Current version: 1.0.0

---
 2025 Craadly. All rights reserved.
