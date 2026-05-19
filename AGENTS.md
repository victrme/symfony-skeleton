# AGENTS.md

No local PHP/Node required. Everything runs through Docker containers.

## Commands via Make (proxy into containers)

| Command | What it does |
|---|---|
| `make php` / `make node` | Shell into container |
| `make pnpm <args>` | Run pnpm in node container |
| `make composer <args>` | Run composer in php container |
| `make symfony <args>` | Run symfony CLI in php container |
| `make check` | PHP lint+format + pnpm lint+format |
| `make fix` | PHP + pnpm lint:fix |

## PHP (inside container: `make php` or `docker compose exec php ...`)

```bash
composer test           # Pest tests (ran as `vendor/bin/pest`)
composer lint           # Rector dry-run + symfony lint:yaml/lint:container/lint:twig/lint:translations
composer format         # php-cs-fixer --dry-run
composer format:fix     # php-cs-fixer apply
```

Single test: `vendor/bin/pest tests/Feature/ExampleTest.php --filter=something`

## Node (inside container: `make node` or `docker compose exec node ...`)

```bash
pnpm dev        # Vite HMR
pnpm build      # Production build
pnpm test       # Vitest (happy-dom, files: tests/**/*.test.ts)
pnpm lint / lint:fix / format / format:fix  # Biome
```

## CI

- **GitHub Actions** in `.github/workflows/` — two workflows: `php.yml` and `node.yml`
- **GitLab CI** in `.gitlab-ci.yml` — mirrors GitHub workflows exactly
- Both run on push/PR to `main`, filtered by changed paths
- PHP: `symfony check:requirements` → `composer run lint` → `composer run test` (requires MariaDB)
- Node: `pnpm run build` → `pnpm run lint` → `pnpm run test`

## Key conventions

- **Routes/templates follow controller directory.** `src/Controller/Hello/MailController.php` → route `/hello/mail`, name `app_hello_mail`, template `templates/pages/hello/mail.html.twig`
- **Controllers:** `#[Route]` attributes, invokable single-action, `final` classes
- **Templates:** Astro-style — `pages/` (one per route), `components/` (reusable partials), `layouts/` (sub-layouts). Split long templates into `_underscore` files in same folder
- **DOM targeting:** `data-*` only, never CSS classes
- **PHP tests:** Pest in `tests/Feature/` (HTTP tests)` and `tests/unit/php/` (logic). `tests/Pest.php` binds Pest to PHPUnit
- **TS tests:** Vitest in `tests/unit/node/`, happy-dom, imports from `assets/scripts/`
- **Fixtures:** `src/Fixtures/` via Foundry factories, loaded with `doctrine:fixtures:load`
- **Formatting:** 4-space indent, 144 max line length, single quotes preferred, no inline comments
- **Linters enforce everything** — `make check` before commit
- **Biome lints only** (formatter disabled), uses `.editorconfig` for formatting rules

## Env files

- `.env` — base, `DATABASE_URL` points to `db` service
- `.env.dev` — `APP_SECRET`, `MAILER_DSN` points to `mailpit:1025`
- `.env.test` — `KERNEL_CLASS`, overridden by CI with `DATABASE_URL` pointing to test MariaDB

## Docker

- Dev: `docker/Dockerfile` (multi-stage: `php` and `node` targets)
- Prod: `docker/prod/Dockerfile` (node-build → composer-build → php, with warmup)
- Dev services: MariaDB, Caddy, phpMyAdmin, Mailpit in `compose.yaml` for dev
