FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update \
  && apt-get install -y --no-install-recommends ca-certificates \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY . /var/www/html

# Copy CA certificate untuk Azure MySQL
COPY certs/DigiCertGlobalRootG2.crt.pem /etc/ssl/certs/DigiCertGlobalRootG2.crt.pem

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
