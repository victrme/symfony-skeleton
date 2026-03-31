# Symfony Skeleton

Symfony 7.4 project running with Docker (PHP, MariaDB, Caddy, Node/Vite, Mailpit).

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

## Makefile commands

### Containers

| Command     | Description                                   |
|-------------|-----------------------------------------------|
| `make init` | Build and start all containers (with rebuild) |
| `make up`   | Start containers                              |
| `make down` | Stop containers                               |
| `make logs` | Follow container logs                         |
| `make php`  | Open a shell in the PHP container             |
| `make node` | Open a shell in the Node container            |

### Tools

| Command                | Description                                    |
|------------------------|------------------------------------------------|
| `make pnpm <args>`     | Run a pnpm command in the Node container       |
| `make composer <args>` | Run a Composer command in the PHP container    |
| `make symfony <args>`  | Run a Symfony CLI command in the PHP container |

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
- Route names must match path names that matches controller directories: `src/Controller/Hello/MailController.php` =>
  `/hello/mail` => `app_hello_mail`. Controller directories should be the source of truth, whenever applicable.
