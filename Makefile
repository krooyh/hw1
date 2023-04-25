up:
	docker-compose up -d --remove-orphans

stop:
	docker-compose stop
	docker-compose rm -f -v

tests: up
	docker-compose run --rm php ./bin/phpunit
	make stop

psalm:
	docker-compose run --rm php ./vendor/bin/psalm.phar