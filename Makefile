init: docker-down \
	docker-pull docker-build docker-up \
	app-composer-install

up: docker-up
down: docker-down
restart: down up
rebuild: down docker-build-no-pull docker-up

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

docker-build-no-pull:
	docker compose build

fix: rebuild app-composer-install app-migrations

app-php-cli-bash:
	docker compose run --rm php-cli bash

app-composer-install:
	docker compose run --rm php-cli composer install

app-migrations:
	docker compose run --rm php-cli bin/console d:m:m --no-interaction