ifneq ("$(wildcard /.dockerenv)","") # if in docker
	EXEC_WWW=
else
	EXEC_WWW=docker compose -p family-meet-api exec frankenphp
endif

BIN_CONSOLE=bin/console --no-debug
VENDOR_CONTAINER=$(shell docker compose ps -q frankenphp)

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

load: build start

ps:
	docker compose ps

sh:
	$(EXEC_WWW) /bin/sh

phpstan:
	$(EXEC_WWW) vendor/bin/phpstan analyse --memory-limit=256M -c phpstan.neon

php-cs-fixer-check:
	$(EXEC_WWW) vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v --dry-run --diff

php-cs-fixer-fix:
	$(EXEC_WWW) vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v --diff

phpunit:
	$(EXEC_WWW) bin/phpunit tests/

cc:
	$(EXEC_WWW) bin/console cache:clear

quality: composer-validate phpstan php-cs-fixer-fix

fixtures:
	$(EXEC_WWW) bin/console doctrine:fixtures:load -q

init-db:
	$(EXEC_WWW) $(BIN_CONSOLE) --if-exists doctrine:database:drop --force
	$(EXEC_WWW) $(BIN_CONSOLE) doctrine:database:create
	$(EXEC_WWW) $(BIN_CONSOLE) doctrine:schema:create
	$(EXEC_WWW) php -d memory_limit=999M $(BIN_CONSOLE) doctrine:fixtures:load -n

init-db-test:
	$(EXEC_WWW) $(BIN_CONSOLE) --env=test --if-exists doctrine:database:drop --force
	$(EXEC_WWW) $(BIN_CONSOLE) --env=test doctrine:database:create
	$(EXEC_WWW) $(BIN_CONSOLE) --env=test doctrine:schema:create
	$(EXEC_WWW) php -d memory_limit=999M $(BIN_CONSOLE) --env=test doctrine:fixtures:load -n

migration-generate:
	$(EXEC_WWW) $(BIN_CONSOLE) doctrine:migrations:diff

migration-migrate:
	$(EXEC_WWW) $(BIN_CONSOLE) doctrine:migrations:migrate -n

bin-install:
	$(EXEC_WWW) composer bin all install -n --prefer-dist

composer-install:
	$(EXEC_WWW) composer install

composer-install-prod:
	$(EXEC_WWW) composer install --no-dev --optimize-autoloader

composer-validate:
	$(EXEC_WWW) composer validate

############################ Production ####################################
build-for-prod:
	docker build --platform linux/amd64 -t cyrilleferand/frankenphp:latest .

push-prod-image:
	docker push cyrilleferand/frankenphp:latest

deploy-prod:
	docker stack deploy -c docker-compose-prod.yml familymeet

prod-context:
	docker context use familymeet-prod

local-context:
	docker context use orbstack
