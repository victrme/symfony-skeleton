

## Base PHP

FROM php:8.5-fpm AS php-base

ARG user=www-html
ARG uid=1000

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN --mount=type=cache,target=/var/cache/apt apt-get update && \
    apt-get install -y --no-install-recommends git unzip && \
    rm -rf /var/lib/apt/lists/*

RUN install-php-extensions intl pdo_mysql zip xdebug apcu && \
    echo "apc.enable_cli=1" > /usr/local/etc/php/conf.d/apcu-cli.ini

RUN groupadd -g $uid $user && \
    useradd -u $uid -g $user -m -G www-data -d /home/$user $user

WORKDIR /var/www/html


## Base Node

FROM node:26-alpine AS node-base

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
