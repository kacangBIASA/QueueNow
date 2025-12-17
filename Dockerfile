# ===== Stage 1: Composer =====
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# ===== Stage 2: PHP =====
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

WORKDIR /var/www/html

# copy source code
COPY . .

# copy vendor dari stage composer
COPY --from=vendor /app/vendor ./vendor

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
