FROM php:8.2-fpm-alpine

WORKDIR /var/www/kodano

RUN apk add --no-cache bash git curl libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev oniguruma-dev zip unzip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd mbstring opcache pdo pdo_mysql

COPY . .

