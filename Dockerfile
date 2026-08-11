FROM php:8.2-apache

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

RUN php artisan storage:link || true

EXPOSE 80

CMD ["apache2-foreground"]
