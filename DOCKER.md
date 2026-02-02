# Running DocuPet with Docker

Runs on **macOS**, Linux, and Windows (with Docker Desktop).

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) (Docker Desktop includes both)

## Quick start

```bash
# Build and start app + MySQL
docker compose up -d --build

# Open in browser
# App: http://localhost:8000
# Vue app (pet registration): http://localhost:8000/vue-app
# Blade pet form: http://localhost:8000/pet-owner/register
```

## Commands

```bash
# View logs
docker compose logs -f app

# Stop
docker compose down

# Stop and remove database volume
docker compose down -v
```

## Environment

Database and app settings are set in `docker-compose.yml`. To override (e.g. `API_KEY`), add a `.env` file in the project root or set env vars before `docker compose up`.

## Local development with Docker

To mount your code and see changes without rebuilding:

1. In `docker-compose.yml`, under `app` → `volumes`, add:
   ```yaml
   volumes:
     - .:/var/www/html
   ```
2. Run `composer install` and `npm run build` on your host, or run a one-off container to build assets.
3. Restart: `docker compose up -d`
