# Symfony Skeleton

Symfony 7.4 LTS starter with only Docker required, a modern frontend toolchain and opinionated conventions.

Changes from `symfony new`:

- **Webpack Encore → Vite 8**: Much faster builds.
- **npm/yarn → pnpm**: faster & better dependency resolution.
- **Nginx → Caddy**: HTTPS in dev.
- **Bootstrap → Tailwind**: for utility class styling.
- **Stimulus removed**: Vue can be added later.

Added from `symfony new`:

- **Docker**: no other requirements.
- **Foundry** for typed fixtures.
- **EasyAdmin 5** for the admin dashboard.
- **Biome** for linting and formatting TS/CSS.
- **Mailpit** for mailer transport in dev.
- **PHPStan + php-cs-fixer** added for static analysis and formatting.

Here is the full stack:

| Layer            | Tool                             |
|------------------|----------------------------------|
| PHP runtime      | PHP 8.4-FPM                      |                               
| Database         | MariaDB                          |                               
| Web server       | Caddy                            |
| Package manager  | pnpm                             |
| Frontend bundler | Vite 8                           |
| CSS              | Tailwind v4 + DaisyUI            |                               
| TS Linter        | Biome                            |                               
| Testing (TS)     | Vitest                           |                               
| Testing (PHP)    | PHPUnit                          |                               
| Admin dashboard  | EasyAdmin 5                      |                               
| Fixtures         | Foundry + DoctrineFixturesBundle |                               
| Static analysis  | PHPStan                          |                               
| PHP formatter    | php-cs-fixer                     |                               
| Mail development | Mailpit                          |                               

## Quick Start

```bash
make init
```

You should be able to access the project with one `make init` command that:

1. Builds containers
2. Installs dependencies
3. Runs migrations
4. Loads fixtures
5. Builds Typescript & CSS assets

Once this is done, go to:

- Project: https://localhost:8443
- Mailpit: http://localhost:8025
- PhpMyAdmin: http://localhost:8080

## Commands

| Command                | Description                          |
|------------------------|--------------------------------------|
| Docker                 |                                      |
| `make init`            | Build, install dependencies, etc     |
| `make up`              | Start containers                     |
| `make down`            | Stop containers                      |
| `make logs`            | Follow container logs                |
| Containers             |                                      |
| `make php`             | Shell into the PHP container         |
| `make node`            | Shell into the Node container        |
| Tools                  |                                      |
| `make pnpm <args>`     | Run pnpm in the Node container       |
| `make composer <args>` | Run Composer in the PHP container    |
| `make symfony <args>`  | Run Symfony CLI in the PHP container |

## Testing

Tests use a dedicated `_test`-suffixed database. `composer test` recreates it automatically before each run:

```bash
# PHPUnit
docker compose exec php composer test

# Vitest
docker compose exec node pnpm test
```

## Linting & Formatting

```bash
# Lint (PHPStan)
make composer lint

# Format (PHP-CS-Fixer)
make composer format
make composer format:fix
```

```bash
# Lint (Biome)
make pnpm lint
make pnpm lint:fix

# Format (Biome)
make pnpm format
make pnpm format:fix
```

Biome is configured in `biome.json`. It respects `.editorconfig` for indentation and enforces single quotes, no semicolons, no trailing commas.

## Conventions

### Routes & Controllers

Route names must match their URL path, which must match the controller's directory.  
The controller directory is the source of truth.

```yaml
# Directory
- src/Controller/Hello/MailController.php

# Route path
- /hello/mail

# Route name
- app_hello_mail
```

### Templates

Templates follow the Astro-style layout convention:

```
templates/
  base.html.twig    ← base
  pages/            ← one .html.twig per route
  components/       ← reusable partials
  layouts/          ← sub-layouts
```

Long .html.twig files can be splitwith smaller sections:

- Entry file and folder has the same name.
- Starting with a _underscore.
- Always in the same folder.

```
templates/
  pages/
    dashboard/
      _articles.html.twig
      _charts.html.twig
      _tables.html.twig
      dashboard.html.twig
```

### Comments

- Block separators use 3 lines of `#` (in YAML/Makefile) or a single PHPDoc/JSDoc block.
- Avoid inline comments unless the logic is non-obvious.

### Docker Images

- Dev services (`db`, `caddy`, `phpmyadmin`, `mailpit`) use `latest` images.
- Production services (`php`, `node`) are pinned to specific versions in `docker/prod/Dockerfile`.
