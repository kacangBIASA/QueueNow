# ====== Stage 1: build frontend (Vite) ======
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ====== Stage 2: PHP + Apache runtime ======
FROM php:8.2-apache

# Apache rewrite for Laravel
RUN a2enmod rewrite

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql zip gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy only composer files first (biar cache build bagus)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy app source
COPY . .

# Copy built frontend assets
COPY --from=frontend /app/public/build ./public/build

# Permissions (untuk storage & cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Apache document root => /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
