FROM php:8.2-apache

# Enable rewrite
RUN a2enmod rewrite

# Install PDO MySQL (untuk Azure/MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copy project ke apache root
WORKDIR /var/www/html
COPY . /var/www/html

# Set document root tetap /var/www/html (root project)
# Kita akan pakai .htaccess untuk route ke index.php

# Permission
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
