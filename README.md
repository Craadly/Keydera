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

## Getting Started

1.  **Log In:** Access your admin dashboard with the credentials created during installation.

2.  **Add Products & Versions:** Use the "Products" section to add your software and upload new versions.

3.  **Generate Licenses:** Create new license keys in the "Licenses" section.

4.  **Integrate:** Use the API helpers in `application/libraries/api_helper_samples/` to connect your application to Keydera for license verification and updates.

---

## API & Cron

* **API Endpoints:** Refer to the `Api_external.php` controller for details on the `/license/verify` and `/update/check` endpoints.

* **Cron Job:** To handle automated tasks, set up a cron job to periodically access the `/cron` endpoint.

    ```bash
    0 0 * * * wget -q -O- [http://your-domain.com/keydera/cron](http://your-domain.com/keydera/cron) >/dev/null 2>&1
    ```

---

## License & Support

This project is licensed under the MIT License. For support or to contribute, please open an issue on the GitHub repository.
