#
# Docker
#

init:
	@docker compose up -d --build --remove-orphans
	@docker compose exec php composer install
	@docker compose exec php symfony console doctrine:migrations:migrate --no-interaction
	@docker compose exec php symfony console doctrine:fixtures:load --no-interaction
	@docker compose exec node pnpm install && pnpm run build

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
# Tools
#

pnpm:
	@docker compose exec node pnpm $(filter-out $@,$(MAKECMDGOALS))

composer:
	@docker compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

symfony:
	@docker compose exec php symfony $(filter-out $@,$(MAKECMDGOALS))

%:
	@:

#
# Scripts
#

check:
	@docker compose exec php composer run lint
	@docker compose exec php composer run format
	@docker compose exec node pnpm run lint
	@docker compose exec node pnpm run format

fix:
	@docker compose exec php composer run lint:fix
	@docker compose exec node pnpm run lint:fix

test:
	@docker compose exec php composer run test
	@docker compose exec node pnpm test
