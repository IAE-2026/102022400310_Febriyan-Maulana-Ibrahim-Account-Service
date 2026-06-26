#!/bin/sh
set -e

cd /app

if [ ! -f .env ]; then
    cp .env.example .env
fi

mkdir -p database storage/api-docs storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    database_path="${DB_DATABASE:-/app/database/database.sqlite}"

    if [ "$database_path" != ":memory:" ]; then
        mkdir -p "$(dirname "$database_path")"
        touch "$database_path"
    fi
fi

php artisan optimize:clear

if [ -z "${APP_KEY:-}" ] && ! grep -Eq '^APP_KEY=base64:.+' .env; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan l5-swagger:generate

exec "$@"
