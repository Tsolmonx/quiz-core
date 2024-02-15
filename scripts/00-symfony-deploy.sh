#!/usr/bin/env bash

echo "Running composer"
composer install --no-dev --optimize-autoloader --no-interaction
# composer install --prefer-dist --no-progress --no-interaction
bin/console assets:install --no-interaction

until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
    (>&2 echo "Waiting for MySQL to be ready...")
	sleep 1
done

echo "Migrating..."
bin/console doctrine:migrations:migrate --no-interaction

echo "generating public.pem, private.pem"
php bin/console lexik:jwt:generate-keypair

echo "Caching config..."
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

echo "Application ready!"
