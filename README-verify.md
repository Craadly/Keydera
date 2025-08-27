# Keydera Frontend Verification (Docker + Playwright)

This guide provides a **ready-to-use PHP environment** (Apache + PHP 8.2) that serves the app from the **repository root** and a **Playwright** runner to capture screenshots for desktop, tablet, and mobile.

## Prerequisites
- Docker Desktop (or Docker Engine) installed and running
- Your project files checked out at the repository root

## Start the PHP Server
Your app’s document root is the repository root. If your existing `docker-compose.yml` sets a different path, update it so that:

```yaml
# excerpt
services:
  web:
    build: ./docker/web
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    restart: unless-stopped
```

Then start:

```bash
docker compose up -d --build
# visit http://localhost:8080
```

> **Note:** This container is isolated from XAMPP; it serves on port **8080** so it won’t conflict with your localhost:80.

## Run Playwright Screenshots
We provide a Playwright image that installs its own browsers. The tests default to `BASE_URL=http://host.docker.internal:8080` (which reaches your web container from the Playwright container on Windows/Mac). You can override `BASE_URL` if needed.

Build the Playwright image and run the tests:

```bash
# Build Playwright image (copies tests/ into the image)
docker build -t keydera-playwright ./docker/playwright

# Run tests using default BASE_URL
docker run --rm \
  -e BASE_URL=http://host.docker.internal:8080 \
  -v "$PWD/tests/ui/screenshots":/work/tests/ui/screenshots \
  -v "$PWD/playwright-report":/work/playwright-report \
  keydera-playwright
```

- **Screenshots** will be saved to `tests/ui/screenshots/`
- **HTML report** will be in `./playwright-report`

On Linux (no `host.docker.internal`), you can use the Docker network of your compose stack instead. Example:

```bash
# Find your compose network name
docker network ls | grep keydera
# Suppose it is keydera_default; get the container name (e.g., keydera-web)
# Then use the service/container name as the host:
docker run --rm \
  --network keydera_default \
  -e BASE_URL=http://keydera-web:80 \
  -v "$PWD/tests/ui/screenshots":/work/tests/ui/screenshots \
  -v "$PWD/playwright-report":/work/playwright-report \
  keydera-playwright
```

## What the Tests Do
- **Important:** You must be logged in for the tests to run correctly.
- Visit the home page at `BASE_URL`
- Capture **expanded** and **collapsed** sidebar states on **desktop** and **tablet**
- Attempt to open **mobile overlay** menu and capture it
- Save screenshots to `tests/ui/screenshots/`

> The script tries common selectors for the sidebar toggle/hamburger (`data-testid`, ARIA labels, or class names). If your project uses different selectors, feel free to update `tests/ui/verify.spec.ts`.

## Troubleshooting
- **Port 8080 in use:** change the left side of the mapping to `9090:80` and set `BASE_URL` accordingly.
- **Document root mismatch:** ensure `APACHE_DOCUMENT_ROOT=/var/www/html` and your code mounts to `/var/www/html`.
- **XAMPP conflict:** none — XAMPP uses port 80; Docker uses 8080.
- **Slow first run:** Docker may download images and install PHP extensions/playwright browsers on first build.
