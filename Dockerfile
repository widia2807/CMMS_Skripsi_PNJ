FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libgd-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install gd zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction --ignore-platform-reqs

RUN cp .env.example .env && php artisan key:generate

EXPOSE 80