FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libgd-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    && docker-php-ext-install gd zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction --ignore-platform-reqs
RUN composer dump-autoload --optimize --ignore-platform-reqs
RUN cp .env.example .env && php artisan key:generate

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

CMD service nginx start && php-fpm