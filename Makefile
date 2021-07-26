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

deploy:
	ssh dev@77.120.110.168 'cd /var/www/backend/ && git pull origin master && php artisan migrate && php artisan l5-swagger:generate'
