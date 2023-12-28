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
	docker compose -p family-meet-api exec php-fpm /bin/sh

phpstan:
	docker compose -p family-meet-api exec php-fpm vendor/bin/phpstan analyse -c phpstan.neon

php-cs-fixer-check:
	docker compose -p family-meet-api exec php-fpm vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v --dry-run --diff

php-cs-fixer-fix:
	docker compose -p family-meet-api exec php-fpm vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix -v --diff

phpunit:
	docker compose -p family-meet-api exec php-fpm bin/phpunit tests/

quality: php-cs-fixer-fix phpstan phpunit

fixtures-test:
	docker compose -p family-meet-api exec php-fpm bin/console --env=test doctrine:fixtures:load
