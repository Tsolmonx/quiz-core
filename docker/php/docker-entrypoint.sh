#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
    echo "IN IF ENTRYPOINT BIIITCH"
    echo "$APP_ENV"

    composer install --no-dev --optimize-autoloader --no-interaction
	# composer install --prefer-dist --no-progress --no-interaction
	bin/console assets:install --no-interaction

	until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
	    (>&2 echo "Waiting for MySQL to be ready...")
		sleep 1
	done

    echo "Migrating..."
    bin/console doctrine:migrations:migrate --no-interaction
fi

exec docker-php-entrypoint "$@"
