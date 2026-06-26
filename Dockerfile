FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libzip-dev \
    libsqlite3-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && chmod +x docker/entrypoint.sh \
    && mkdir -p database storage/api-docs storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 database storage bootstrap/cache

ENTRYPOINT ["docker/entrypoint.sh"]

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
