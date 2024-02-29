ifneq ("$(wildcard /.dockerenv)","") # if in docker
	EXEC_WWW=
else
	EXEC_WWW=docker compose -p family-meet-api exec php-fpm
endif

BIN_CONSOLE=bin/console --no-debug
VENDOR_CONTAINER=$(shell docker compose ps -q php-fpm)

start:
	docker compose up -d

stop:
	docker compose stop

down:
	docker compose down

build:
	docker compose build --no-cache

restart: stop start

reload: down build start

ps:
	docker compose ps

sh:
	$(EXEC_WWW) /bin/sh

phpstan:
	$(EXEC_WWW) vendor/bin/phpstan analyse -c phpstan.neon

php-cs-fixer-check:
	$(EXEC_WWW) vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v --dry-run --diff

php-cs-fixer-fix:
	$(EXEC_WWW) vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v --diff

phpunit:
	$(EXEC_WWW) bin/phpunit tests/

quality: composer-validate php-cs-fixer-fix phpstan phpunit

fixtures-test:
	$(EXEC_WWW) bin/console doctrine:fixtures:load -q

init-db:
	#make clean-db
	$(EXEC_WWW) $(BIN_CONSOLE) --if-not-exists doctrine:database:create
	$(EXEC_WWW) $(BIN_CONSOLE) doctrine:schema:create
	$(EXEC_WWW) php -d memory_limit=999M $(BIN_CONSOLE) doctrine:fixtures:load -n

init-db-test:
	$(EXEC_WWW) $(BIN_CONSOLE) --env=test --if-not-exists doctrine:database:create
	$(EXEC_WWW) $(BIN_CONSOLE) --env=test doctrine:schema:create
	$(EXEC_WWW) php -d memory_limit=999M $(BIN_CONSOLE) --env=test doctrine:fixtures:load -n

bin-install:
	$(EXEC_WWW) composer bin all install -n --prefer-dist

composer-install:
	$(EXEC_WWW) composer install

composer-validate:
	$(EXEC_WWW) composer validate
