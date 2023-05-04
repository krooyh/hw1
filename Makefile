up:
	docker-compose up -d --remove-orphans

stop:
	docker-compose stop
	docker-compose rm -f -v

tests-up:
	docker-compose -f docker-compose-tests.yml up -d --remove-orphans

stop-tests:
	docker-compose -f docker-compose-tests.yml stop
	docker-compose -f docker-compose-tests.yml rm -f -v

tests: stop-tests tests-up psalm init_db_tests
	docker-compose run --rm php ./bin/phpunit
	make stop-tests

psalm:
	docker-compose run --rm php ./vendor/bin/psalm.phar

prepare_db:
	docker-compose run --rm php bin/console bin/console doctrine:database:create
	docker-compose run --rm php bin/console doctrine:migrations:migrate

init_db_tests:
	docker-compose -f docker-compose-tests.yml run --rm php_tests bin/console doctrine:cache:clear-metadata
	docker-compose -f docker-compose-tests.yml run --rm php_tests bin/console doctrine:schema:create --env=test

build_docker_php:
	docker-compose run --rm composer install --optimize-autoloader --ignore-platform-reqs
	docker-compose run --rm bin/console cache:clear
	docker-compose run --rm bin/console cache:warmup

build: up build_docker_php prepare_db stop

