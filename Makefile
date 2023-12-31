ifneq ("$(wildcard /.dockerenv)","") # if in docker
	EXEC_WWW=
else
	EXEC_WWW=docker compose -p family-meet-api exec php-fpm
endif

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

quality: php-cs-fixer-fix phpstan phpunit

fixtures-test:
	$(EXEC_WWW) bin/console --env=test doctrine:fixtures:load

init-db:
	make clean-db
	$(EXEC_WWW) $(APP_CONSOLE) doctrine:schema:create
	$(EXEC_WWW) php -d memory_limit=999M $(APP_CONSOLE) doctrine:fixtures:load -n

bin-install:
	$(EXEC_WWW) composer bin all install -n --prefer-dist

copy-docker-vendors:
	$(EXEC_WWW) composer install
	#docker cp $(VENDOR_CONTAINER):/var/www/vendor vendor
