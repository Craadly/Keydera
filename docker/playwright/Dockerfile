FROM mcr.microsoft.com/playwright:v1.46.0-jammy

WORKDIR /work

# Preinstall Playwright test runner
RUN npm init -y \
 && npm i -D @playwright/test@1.46.0 \
 && npx playwright install --with-deps

# Copy tests into the container path
COPY tests /work/tests

# Default env; override BASE_URL when running if needed
ENV BASE_URL=http://host.docker.internal:8080

# Default command runs our verify spec; you can override in docker compose/run
CMD ["npx", "playwright", "test", "tests/ui/verify.spec.ts", "--reporter=html"]
