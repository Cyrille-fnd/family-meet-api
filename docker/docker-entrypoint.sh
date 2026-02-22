#!/bin/sh
set -e

# Attendre la base de données
echo "Waiting for database..."
until php bin/console dbal:run-sql -q "SELECT 1" 2>/dev/null; do
    sleep 1
done

# Exécuter les migrations
if ls migrations/*.php 1>/dev/null 2>&1; then
    echo "Running migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing
fi

exec docker-php-entrypoint "$@"
