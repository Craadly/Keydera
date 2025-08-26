# Keydera API Reference

This document provides a reference for the external API endpoints available in Keydera. These endpoints allow you to integrate your applications with the Keydera licensing and update system.

## Authentication

All API requests must include a valid API key. The API key should be sent in the `X-API-KEY` header of your request.

```
X-API-KEY: YOUR_API_KEY
```

You can generate API keys from the Keydera admin panel under **Settings > API Settings**.

---

## Endpoints

### 1. Verify License

This endpoint allows you to verify a customer's license key for a specific product.

*   **URL:** `/api/verify-license`
*   **Method:** `POST`
*   **Content-Type:** `application/json`

#### Request Body

| Parameter     | Type   | Required | Description                               |
|---------------|--------|----------|-------------------------------------------|
| `product_id`  | string | Yes      | The unique ID of your product.            |
| `license_key` | string | Yes      | The customer's license key to validate.   |
| `client_name` | string | No       | The client's name or domain for activation tracking. |

**Example Request:**
```json
{
    "product_id": "your_product_123",
    "license_key": "XXXX-XXXX-XXXX-XXXX",
    "client_name": "example.com"
}
```

#### Responses

**Success (200 OK): License is valid**
```json
{
    "status": "success",
    "message": "License is valid.",
    "data": {
        "license_key": "XXXX-XXXX-XXXX-XXXX",
        "status": "active",
        "client_name": "example.com",
        "activations_left": 9,
        "expires_at": "2025-12-31 23:59:59"
    }
}
```

**Error (400 Bad Request): Invalid input**
```json
{
    "status": "error",
    "message": "Invalid input. `product_id` and `license_key` are required."
}
```

**Error (404 Not Found): License not found or invalid**
```json
{
    "status": "error",
    "message": "The provided license key is invalid or does not exist."
}
```

**Error (403 Forbidden): License expired or suspended**
```json
{
    "status": "error",
    "message": "This license has expired or has been suspended."
}
```
---

### 2. Check for Updates

This endpoint allows your application to check if a new version is available for a given product.

*   **URL:** `/api/check-updates`
*   **Method:** `POST`
*   **Content-Type:** `application/json`

#### Request Body

| Parameter         | Type   | Required | Description                               |
|-------------------|--------|----------|-------------------------------------------|
| `product_id`      | string | Yes      | The unique ID of your product.            |
| `current_version` | string | Yes      | The current version of the application (e.g., "1.0.0"). |

**Example Request:**
```json
{
    "product_id": "your_product_123",
    "current_version": "1.0.0"
}
```

#### Responses

**Success (200 OK): Update is available**
```json
{
    "status": "success",
    "message": "A new version is available.",
    "data": {
        "latest_version": "1.1.0",
        "release_date": "2025-01-15",
        "changelog": "- Added new feature X.\n- Fixed bug Y."
    }
}
```

**Success (200 OK): No update available**
```json
{
    "status": "success",
    "message": "You are on the latest version."
}
```

**Error (404 Not Found): Product not found**
```json
{
    "status": "error",
    "message": "The specified product does not exist."
}
```
---

### 3. Download Update

This endpoint provides a secure URL to download the latest version of a product, provided the license is valid.

*   **URL:** `/api/download-update`
*   **Method:** `POST`
*   **Content-Type:** `application/json`

#### Request Body

| Parameter     | Type   | Required | Description                               |
|---------------|--------|----------|-------------------------------------------|
| `product_id`  | string | Yes      | The unique ID of your product.            |
| `license_key` | string | Yes      | The customer's license key.               |

**Example Request:**
```json
{
    "product_id": "your_product_123",
    "license_key": "XXXX-XXXX-XXXX-XXXX"
}
```

#### Responses

**Success (200 OK): Download link provided**

The API will respond with a 302 Redirect to a temporary, secure download URL for the zip file. Your HTTP client should be configured to follow redirects.

**Error (403 Forbidden): Invalid or expired license**
```json
{
    "status": "error",
    "message": "Your license does not permit access to this download."
}
```

**Error (404 Not Found): No update file available**
```json
{
    "status": "error",
    "message": "No update file is available for this product."
}
```
