init:
	echo "hello world"

up:
	@docker compose up -d

php:
	@docker compose exec -it php bash

node:
	@docker compose exec -it node bash
