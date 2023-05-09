up:
	docker-compose up -d --remove-orphans

stop:
	docker-compose stop
	docker-compose rm -f -v

tests-up:
	docker-compose -f docker-compose-tests.yml up -d --remove-orphans
	make init-db-tests

tests-stop:
	docker-compose -f docker-compose-tests.yml stop
	docker-compose -f docker-compose-tests.yml rm -f -v

tests: tests-stop tests-up _psalm-tests init-db-tests
	docker-compose -f docker-compose-tests.yml run --rm php composer install --optimize-autoloader --ignore-platform-reqs
	docker-compose -f docker-compose-tests.yml run --rm php_tests ./bin/phpunit
	make tests-stop

psalm:
	docker-compose run --rm php ./vendor/bin/psalm.phar

_psalm-tests:
	docker-compose -f docker-compose-tests.yml run --rm php_tests ./vendor/bin/psalm.phar

init-db:
	docker-compose run --rm php bin/console --no-interaction doctrine:migrations:migrate

init-db-tests:
	docker-compose -f docker-compose-tests.yml run --rm php_tests bin/console doctrine:cache:clear-metadata
	docker-compose -f docker-compose-tests.yml run --rm php_tests bin/console doctrine:schema:create --env=test

build-docker-php:
	docker-compose run --rm php composer install --optimize-autoloader --ignore-platform-reqs
	docker-compose run --rm php bin/console cache:clear
	docker-compose run --rm php bin/console cache:warmup

build: up build-docker-php init-db stop
