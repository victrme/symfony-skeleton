init:
	echo "hello world"

b:
	docker exec -it symfony-docker-php-1 bash

logs-b:
	docker compose logs -f
