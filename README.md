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

File structure:

```
tests/
   ├── functional/      # PHP - HTTP tests
   ├── unit/
   │   ├── php/         # PHP - Logic tests
   │   └── node/        # TypeScript - DOM tests
   └── bootstrap.php
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

Biome is configured in `biome.json`. Uses `.editorconfig` first.

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

Long .html.twig files can be split with smaller sections:

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

### Formatting

Formatting must at least match theses rules:

- Always use spaces
- Indent with 4 spaces
- Lines must be below 144 characters
- Use single quotes when possible

**Note:** Some files require different formatting style (Makefile with tabs, JSON with double quotes, etc). But logic files, PHP & JS/TS, must
be
formatted as mentioned above.

### Comments

- Block separators use at least 3 lines of `#` (in YAML/Makefile) or a single PHPDoc/JSDoc block.
- Avoid inline comments, try to explain difficult logic in block comments.

```yaml
#
# Optionals
#

mailpit:
    image: axllent/mailpit
```

```ts
/*
 * Retries a fetch with exponential backoff.
 * Needed because the external API occasionally returns 429 on burst traffic.
 */
function fetchWithRetry(url: string): Promise<Response> {
```

```php
$map = [
  \T_PUBLIC => \T_PUBLIC_SET,
  \T_PROTECTED => \T_PROTECTED_SET,
  \T_PRIVATE => \T_PRIVATE_SET,
];

/*
 * Emulate token
 */

array_splice($tokens, $i, 4, [
  new Token(
    $map[$token->id], $token->text . '(' . $tokens[$i + 2]->text . ')',
    $token->line, $token->pos),
]);
```

### Docker Images

- Dev services (`db`, `caddy`, `phpmyadmin`, `mailpit`) use `latest` images.
- Production services (`php`, `node`) are pinned to specific versions in `docker/prod/Dockerfile`.
