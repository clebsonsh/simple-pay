FROM php:8.5-fpm-alpine AS base

RUN apk update && apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    shadow \
    pcre-dev ${PHPIZE_DEPS} \
    && MAKEFLAGS="-j $(nproc)" pecl install redis pcov \
    && docker-php-ext-enable redis pcov \
    && apk del pcre-dev ${PHPIZE_DEPS}

RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
    pcntl \
    pdo \
    pdo_mysql

ARG USER=laravel

RUN addgroup -S ${USER} && adduser -S ${USER} -G ${USER}

WORKDIR /var/www/html


FROM base AS composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY --chown=$USER:$USER . .

RUN composer install --no-interaction


FROM base AS final

COPY --chown=$USER:$USER --from=composer /app/vendor /var/www/html/vendor

COPY --chown=$USER:$USER . /var/www/html

COPY --chown=$USER:$USER .env.docker /var/www/html/.env

RUN php artisan key:generate

USER $USER

EXPOSE 8080
