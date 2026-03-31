#
# Docker
#

init:
	@docker compose up -d --build --remove-orphans

up:
	@docker compose up -d

down:
	@docker compose down -t 0

logs:
	@docker compose logs -f

#
# Services
#

php:
	@docker compose exec -it php bash

node:
	@docker compose exec -it node bash

#
# Tools & scripts
#

dev:
	@docker compose exec node pnpm run dev

pnpm:
	@docker compose exec node pnpm $(filter-out $@,$(MAKECMDGOALS))

composer:
	@docker compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

symfony:
	@docker compose exec php symfony $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
