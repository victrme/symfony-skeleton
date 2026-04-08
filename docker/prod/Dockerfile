#
# Node
#

FROM node:25.8.2 AS node-build

WORKDIR /var/www/html

RUN npm install --global pnpm

COPY package.json pnpm-lock.yaml ./
RUN pnpm install --frozen-lockfile

COPY assets/ assets/
COPY tsconfig.json vite.config.ts ./
RUN pnpm run build

#
# Composer
#

FROM composer:latest AS composer-build

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --optimize-autoloader \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

#
# PHP
#

FROM php:8.4.19-fpm AS php

ARG user=www-html
ARG uid=1000

RUN --mount=type=cache,target=/var/cache/apt \
    apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libicu-dev && \
    docker-php-ext-install intl pdo_mysql zip opcache && \
    rm -rf /var/lib/apt/lists/*

COPY --link docker/prod/php.ini /usr/local/etc/php/conf.d/prod.ini

RUN groupadd -g $uid $user && \
    useradd -u $uid -g $user -m -G www-data -d /home/$user $user

WORKDIR /var/www/html

COPY --chown=$user:$user . .
COPY --from=composer-build --chown=$user:$user /app/vendor/ vendor/
COPY --from=node-build --chown=$user:$user /var/www/html/public/build/ public/build/

USER $user

RUN APP_ENV=prod APP_SECRET=unused php bin/console cache:warmup
