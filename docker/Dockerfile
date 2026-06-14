

## Base PHP

FROM php:8.4.19-fpm AS php-base

ARG user=www-html
ARG uid=1000

RUN --mount=type=cache,target=/var/cache/apt apt-get update && \
    apt-get install -y --no-install-recommends git unzip libzip-dev libicu-dev && \
    docker-php-ext-install intl pdo_mysql zip opcache && \
    rm -rf /var/lib/apt/lists/*

RUN groupadd -g $uid $user && \
    useradd -u $uid -g $user -m -G www-data -d /home/$user $user

WORKDIR /var/www/html


## Base Node

FROM node:26.1.0-alpine AS node-base

WORKDIR /var/www/html

USER root
RUN npm install --global pnpm && \
    mkdir -p /home/node/.local/share/pnpm/store && \
    chown -R node:node /home/node/.local /var/www/html

USER node
RUN pnpm config set store-dir /home/node/.local/share/pnpm/store


## Development PHP

FROM php-base AS php-dev

ARG user=www-html

COPY --link --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --link --from=ghcr.io/symfony-cli/symfony-cli:latest /usr/local/bin/symfony /usr/local/bin/symfony

RUN echo "memory_limit=1G" > /usr/local/etc/php/conf.d/memory-limit.ini

COPY --chown=$user:$user . .
USER $user


## Development Node

FROM node-base AS node-dev


## Build Composer

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


## Build Node

FROM node-base AS node-build

COPY package.json pnpm-lock.yaml ./
RUN pnpm install --frozen-lockfile

COPY assets/ assets/
COPY tsconfig.json vite.config.ts ./
RUN pnpm run build


## Production PHP

FROM php-base AS php-prod

ARG user=www-html

COPY --link docker/php.ini /usr/local/etc/php/conf.d/prod.ini

COPY --chown=$user:$user . .

COPY --from=composer-build --chown=$user:$user /app/vendor/ vendor/
COPY --from=node-build --chown=$user:$user /var/www/html/public/build/ public/build/

USER $user

RUN php bin/console cache:warmup
