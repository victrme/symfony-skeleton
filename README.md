# Symfony Skeleton

Symfony 7.4 project running with Docker (PHP, MariaDB, Caddy, Node/Vite, Mailpit).

## Requirements

- Docker + Docker Compose
- Make (optional, but convenient)

## Quick Start

1. Build and start containers:

```bash
make init
```

2. Install PHP dependencies:

```bash
docker compose exec php composer install
```

3. (Optional) Install frontend dependencies:

```bash
docker compose exec node pnpm install
```

4. Open the app:

- HTTPS app: `https://localhost:8443`
- Mailpit: `http://localhost:8025`
- phpMyAdmin: `http://localhost:8080`

## Useful Commands

- Start containers: `make up`
- Stop containers: `make down`
- Follow logs: `make logs`
- Open PHP shell: `make php`
- Open Node shell: `make node`

## Running Tests

The test environment uses a dedicated database with `_test` suffix (for example: `db_symfony_test`).

Prepare test DB once:

```bash
docker compose exec php php bin/console doctrine:database:create --env=test
docker compose exec php php bin/console doctrine:migrations:migrate --env=test -n
```

Run tests:

```bash
docker compose exec php vendor/bin/phpunit
```

If migrations are not available yet, use:

```bash
docker compose exec php php bin/console doctrine:schema:create --env=test
```

## Environment

Main database connection is configured via `DATABASE_URL` in `.env`.

Symfony test runs with `APP_ENV=test` (see `phpunit.xml`).

## Thoughts to re-organise

- Comments are used as separators, 3 lines of "#" or a single PHPDoc/JSDoc
- Development docker compose services are using the latest images
- Production services (PHP, Node) are tied to a specific version
- Templates are organized like Astro projects, with `pages/`, `components/`, and `layouts/`
- PHP only handles PHP, all the frontend is delegated to Node, including formatting & linting
