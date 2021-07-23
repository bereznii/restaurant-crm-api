down: docker-down
up: docker-up
restart: docker-down docker-up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

composer-install:
	docker-compose run --rm php-fpm composer install

command:
	docker exec -it crm-sm-backend_php-fpm_1 sh -c "php artisan migrate"