# syntax=docker/dockerfile:1

############################
# 1) Vendor (Composer) stage
############################
FROM php:8.2-cli-bookworm AS vendor

RUN apt-get update && apt-get install -y \
    git unzip curl ca-certificates \
    libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libsqlite3-dev pkg-config \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql pdo_sqlite zip gd \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

COPY . .

############################
# 2) Frontend build stage
############################
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

############################
# 3) Runtime stage
############################
FROM php:8.2-apache-bookworm AS runtime

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libsqlite3-dev pkg-config \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql pdo_sqlite zip gd \
  && apt-get purge -y --auto-remove \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libsqlite3-dev pkg-config \
  && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/*.conf \
  /etc/apache2/apache2.conf \
  /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
