# Keydera API Documentation

Keydera's external API provides comprehensive licensing and update management endpoints. This page documents all available endpoints and their request/response shapes based on the implementation in this repository.

Base URL examples:
- http://your-domain.tld/ (root)
- http://your-domain.tld/api/... (endpoints below)

## Authentication and required headers

Send these headers with every API call:

- LB-API-KEY: Your API key (generate in Settings → API Settings)
- LB-URL: The full URL of the client installation (e.g., https://app.example.com)
- LB-IP: The client's public IP address
- LB-LANG: Language for API responses (english, chinese, german, portuguese)
- User-Agent: Optional but recommended

Body content for POST requests should be JSON unless noted.

Common response envelope:
- status: boolean
- message: human‑readable string
- data: object|null (endpoint‑specific)

HTTP status codes used: 200 (application‑level success/failure), 400 (bad request), 401 (unauthorized/blocked), 403 (forbidden/invalid headers), 404 (not found), plus file responses for downloads.

---

## Endpoints

### 1) Check connection
- URL: POST /api/check_connection_ext
- Use: Quick health probe to validate headers and reachability.
- Response: { status: true, message: "Connection successful." }

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/check_connection_ext' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english'
```

### 2) Latest version info
- URL: POST /api/latest_version
- Body: { product_id: string }
- Success 200: { status, message, product_name, latest_version, release_date, summary }
- If product inactive/not found: latest_version fields are null with a descriptive message (status: false).

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/latest_version' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "E7C1D9E7"
}'
```

### 3) Check update availability
- URL: POST /api/check_update
- Body: { product_id: string, current_version: string }
- Success 200 when update exists: { status: true, message, version, release_date, summary, changelog, update_id, has_sql }
- Success 200 when no update: { status: false, message }
- Errors 200/400 with message when product invalid/inactive or values missing.

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/check_update' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "E7C1D9E7",
    "current_version": "v0.0.0"
}'
```

### 4) Get update file size (HEAD)
- URL: HEAD /api/get_update_size/{type}/{vid}
  - type: "main" | "sql"
  - vid: version id returned by check_update
- Response: File headers (Content-Length, Content-Type) if present; 404 if missing/invalid.

Alternate query style is supported: HEAD /api/get_update_size?type=main&vid=123

cURL example:
```bash
curl --location --head 'https://your-keydera-server/api/get_update_size/main/c47d2873d07ad2451d74' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english'
```

### 5) Download update
- URL: POST /api/download_update/{type}/{vid}
  - type: "main" | "sql"
  - vid: version id
- When the product requires valid licenses for updates, include either:
  - license_file: string (encrypted blob returned by activate_license)
  - or license_code: string and client_name: string
- Success: Binary file response (ZIP for main, SQL for sql). 401/404 for failures.

Alternate query style is supported: POST /api/download_update?type=main&vid=123

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/download_update/main/c47d2873d07ad2451d74' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "license_code": "AFDB-6368-E711-D1F8",
    "client_name": "john"
}'
```

### 6) Activate license
- URL: POST /api/activate_license
- Body (regular license): { product_id: string, license_code: string, client_name: string, verify_type?: "non_envato" }
- Body (Envato purchase): { product_id: string, license_code: string, client_name: string, verify_type: "envato" }
- Success 200: { status: true, message, data: any|null, lic_response: string }
  - lic_response is an encrypted token your app can store and reuse as license_file for future calls.
- Failure 200: { status: false, message, data: null, lic_response: null }

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/activate_license' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "verify_type": "non_envato",
    "product_id": "E7C1D9E7",
    "license_code": "AFDB-6368-E711-D1F8",
    "client_name": "john"
}'
```

### 7) Verify license
- URL: POST /api/verify_license
- Body: either
  - { product_id: string, license_code: string, client_name: string }
  - or { product_id: string, license_file: string }
- Success 200: { status: true, message, data: any|null }
- Failure 200: { status: false, message }

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/verify_license' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "E7C1D9E7",
    "license_code": "AFDB-6368-E711-D1F8",
    "client_name": "john"
}'
```

### 8) Deactivate license
- URL: POST /api/deactivate_license
- Body: either
  - { product_id: string, license_code: string, client_name: string }
  - or { product_id: string, license_file: string }
- Success 200: { status: true, message }
- Failure 200: { status: false, message }

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/deactivate_license' \
--header 'LB-API-KEY: your_external_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "E7C1D9E7",
    "license_code": "AFDB-6368-E711-D1F8",
    "client_name": "john"
}'
```

---

## Notes and behaviors

- Headers validation: Requests missing or with invalid LB-URL/LB-IP are rejected (403).
- Rate limiting: If enabled in Settings, requests may be rate‑limited per API key.
- Blacklists: Requests from blocked IPs/domains are denied when configured.
- Domains/IP binding: Licenses can be restricted to specific domains and/or IPs.
- Expiry: Licenses may expire by date or by days since first activation.
- Update eligibility: If updates_till/support period is set and expired, update download will be denied.

---

## Minimal examples

PowerShell (Windows) example using Invoke-WebRequest:

```powershell
$headers = @{ 'LB-API-KEY'='YOUR_KEY'; 'LB-URL'='https://app.example.com'; 'LB-IP'='203.0.113.10'; 'LB-LANG'='english' }
Invoke-RestMethod -Method Post -Uri 'http://localhost:8080/api/check_connection_ext' -Headers $headers
```

Check update:

```powershell
$headers = @{ 'LB-API-KEY'='YOUR_KEY'; 'LB-URL'='https://app.example.com'; 'LB-IP'='203.0.113.10'; 'LB-LANG'='english' }
$body = @{ product_id = 'your_product_123'; current_version = '1.0.0' } | ConvertTo-Json
Invoke-RestMethod -Method Post -Uri 'http://localhost:8080/api/check_update' -Headers $headers -ContentType 'application/json' -Body $body
```

---

## Internal Admin API (server-to-server)

These endpoints allow managing products and licenses programmatically. They use the same headers as the external API (LB-API-KEY, LB-URL, LB-IP, LB-LANG).

All endpoints are POST and accept JSON bodies. Responses use the same envelope: status, message (optional), and fields as documented below.

### 1) Check connection (internal)
- URL: POST /api/check_connection_int
- Response: { status: true, message: "Connection successful." }

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/check_connection_int' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english'
```

### 2) Add product
- URL: POST /api/add_product
- Body:
  - product_id?: string (alphanumeric; auto‑generated if omitted)
  - product_name: string (required)
  - envato_item_id?: string|number
  - product_details?: string
- Success 200: { status: true, message, product_id }
- Failure: 200/400 with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/add_product' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_name": "ABC Software",
    "product_id": "7EX821PQ"
}'
```

### 3) Get product
- URL: POST /api/get_product
- Body: { product_id: string }
- Success 200:
  - {
    status: true,
    product_id, envato_item_id, product_name, product_details,
    latest_version, latest_version_release_date,
    is_product_active: boolean,
    requires_license_for_downloading_updates: boolean
  }
- Failure 200 with message when not found

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/get_product' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "7EX821PQ"
}'
```

### 4) List products
- URL: POST /api/get_products
- Success 200: { status: true, products: [ same fields as Get product (per item) ] }

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/get_products' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english'
```

### 5) Mark product active
- URL: POST /api/mark_product_active
- Body: { product_id: string }
- Response 200: status true with message or status false if already active/not found

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/mark_product_active' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "7EX821PQ"
}'
```

### 6) Mark product inactive
- URL: POST /api/mark_product_inactive
- Body: { product_id: string }
- Response 200: status true with message or status false if already inactive/not found

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/mark_product_inactive' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "7EX821PQ"
}'
```

### 7) Create license
- URL: POST /api/create_license
- Body:
  - product_id: string (required)
  - license_code?: string (generated if omitted)
  - client_name?: string
  - license_type?: string
  - invoice_number?: string
  - client_email?: string (validated)
  - comments?: string
  - licensed_ips?: string (CSV, validated)
  - licensed_domains?: string (CSV, validated, normalized without http/www)
  - support_end_date?: string (Y-m-d or Y-m-d H:i:s)
  - updates_end_date?: string (Y-m-d or Y-m-d H:i:s)
  - expiry_date?: string (Y-m-d or Y-m-d H:i:s)
  - expiry_days?: number
  - license_uses?: number
  - license_parallel_uses?: number
- Success 200: { status: true, message, license_code }
- Failure 200/400 with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/create_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "7EX821PQ",
    "invoice_number": "#JG-3744",
    "license_code": "180PM-XE2I-4YIX-ZVBR",
    "license_type": "Regular",
    "client_name": "john",
    "client_email": "john@example.com",
    "licensed_ips": "127.0.0.1,192.168.1.1",
    "licensed_domains": "localhost,test.example.com",
    "support_end_date": "2023-06-08 12:00:00",
    "updates_end_date": "2024-04-06 02:00:00",
    "expiry_date": "2025-02-02 12:00:00",
    "expiry_days": 30,
    "license_uses": 3,
    "license_parallel_uses": 1,
    "comments": "Lead converted from SalesForce CRM"
}'
```

### 8) Edit license
- URL: POST /api/edit_license
- Body:
  - license_code: string (required)
  - product_id?: string (defaults to existing)
  - Other fields mirror Create license. Special rule: sending an empty string sets that field to null; omitting the field leaves it unchanged.
- Success 200: { status: true, message, license_code }
- Failure 200/400 with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/edit_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": "7EX821PQ",
    "invoice_number": "#AF-744",
    "license_code": "180PM-XE2I-4YIX-ZVBR",
    "license_type": "Extended",
    "client_name": "john",
    "client_email": "john@example.com",
    "licensed_ips": "127.0.0.1,192.168.1.1,192.168.1.2",
    "licensed_domains": "localhost,test.example.com",
    "support_end_date": "",
    "updates_end_date": "2025-02-02 12:00:00",
    "expiry_date": "2025-02-02 12:00:00",
    "expiry_days": "",
    "license_uses": 5,
    "license_parallel_uses": 3,
    "comments": "User upgraded to Extended license"
}'
```

### 9) Get license
- URL: POST /api/get_license
- Body: { license_code: string }
- Success 200: {
  status: true,
  license_code, product_id, product_name,
  license_type, client_name, client_email, invoice_number,
  license_comments, licensed_ips, licensed_domains,
  uses, uses_left, parallel_uses, parallel_uses_left,
  license_expiry, support_expiry, updates_expiry,
  date_modified,
  is_blocked: boolean,
  is_a_envato_purchase_code: boolean,
  is_valid_for_future_activations: boolean
}
- Failure 200 with message if not found

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/get_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "license_code": "180PM-XE2I-4YIX-ZVBR"
}'
```

### 10) Search license
- URL: POST /api/search_license
- Body: { keyword: string }
- Success 200 when results: {
  status: true, results_count: number, results: [ { product_id, license_code, license_type, client_name, client_email } ]
}
- Success 200 when none: { status: true, results: "No corresponding license was found." }
- Failure 400 when keyword missing

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/search_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "keyword": "180PM"
}'
```

### 11) Delete license
- URL: POST /api/delete_license
- Body: { license_code: string }
- Response 200: status true/false with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/delete_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "license_code": "180PM-XE2I-4YIX-ZVBR"
}'
```

### 12) Deactivate all activations for a license
- URL: POST /api/deactivate_license_activations
- Body: { license_code: string }
- Response 200: status true/false with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/deactivate_license_activations' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "license_code": "180PM-XE2I-4YIX-ZVBR"
}'
```

### 13) Block license
- URL: POST /api/block_license
- Body: { license_code: string }
- Response 200: status true/false with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/block_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "license_code": "180PM-XE2I-4YIX-ZVBR"
}'
```

### 14) Unblock license
- URL: POST /api/unblock_license
- Body: { license_code: string }
- Response 200: status true/false with message

cURL example:
```bash
curl --location --request POST 'https://your-keydera-server/api/unblock_license' \
--header 'LB-API-KEY: your_internal_api_key' \
--header 'LB-URL: https://example.org' \
--header 'LB-IP: 127.0.0.1' \
--header 'LB-LANG: english' \
--header 'Content-Type: application/json' \
--data-raw '{
    "license_code": "180PM-XE2I-4YIX-ZVBR"
}'
```

---

## Internal API examples (PowerShell)

Add product:

```powershell
$headers = @{ 'LB-API-KEY'='YOUR_KEY'; 'LB-URL'='https://admin.example.com'; 'LB-IP'='203.0.113.10'; 'LB-LANG'='english' }
$body = @{ product_name = 'Awesome App'; envato_item_id = '12345678' } | ConvertTo-Json
Invoke-RestMethod -Method Post -Uri 'http://localhost:8080/api/add_product' -Headers $headers -ContentType 'application/json' -Body $body
```

Create license:

```powershell
$headers = @{ 'LB-API-KEY'='YOUR_KEY'; 'LB-URL'='https://admin.example.com'; 'LB-IP'='203.0.113.10'; 'LB-LANG'='english' }
$body = @{
  product_id = 'your_product_123'
  client_name = 'Acme Ltd'
  licensed_domains = 'acme.com,app.acme.com'
  license_uses = 5
  license_parallel_uses = 2
} | ConvertTo-Json
Invoke-RestMethod -Method Post -Uri 'http://localhost:8080/api/create_license' -Headers $headers -ContentType 'application/json' -Body $body
```