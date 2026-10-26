FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
        libpq-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install zip \
    && a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/framework/testing \
    && chmod -R 777 storage bootstrap/cache \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && php artisan storage:link || true

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
