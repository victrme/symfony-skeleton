init:
	@docker compose up -d --build --remove-orphans

up:
	@docker compose up -d

down:
	@docker compose down -t 0

php:
	@docker compose exec -it php bash

node:
	@docker compose exec -it node bash

logs:
	@docker compose logs -f

dev:
	@docker compose exec node pnpm run dev

pnpm:
	@docker compose exec node pnpm $(filter-out $@,$(MAKECMDGOALS))

composer:
	@docker compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
