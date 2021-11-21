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
	make swagger && ssh dev@77.120.110.168 'cd /var/www/backend/ && git pull origin master && composer install && php artisan migrate && php artisan config:clear'

rollback:
	php artisan migrate:rollback --step=1

swagger:
	php artisan l5-swagger:generate
