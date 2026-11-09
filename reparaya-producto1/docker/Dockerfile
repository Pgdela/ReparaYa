FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev libicu-dev unzip git \
    && docker-php-ext-install pdo_mysql mysqli zip intl exif \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html