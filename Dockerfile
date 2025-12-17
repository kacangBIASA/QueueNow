# =========================
# Stage 1: Vendor (Composer)
# =========================
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# =========================
# Stage 2: PHP Runtime
# =========================
FROM php:8.2-fpm

# Install system deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy app source
COPY . .

# Copy vendor from stage 1
COPY --from=vendor /app/vendor ./vendor

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
