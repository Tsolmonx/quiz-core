#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --optimize-autoloader

echo "Caching config..."
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

# Attempt database connection in a loop with exponential backoff
attempt=1
max_attempts=5  # Adjust as needed, based on expected connection retries
wait_time=1      # Adjust as needed, based on retry intervals

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
	until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
	    (>&2 echo "Waiting for DB to be ready...")
		sleep 1
	done

	echo "Migrating..."
    bin/console doctrine:migrations:migrate --no-interaction
    echo "Database migrations completed successfully."
fi

# Further application or script actions here
echo "Application ready!"
