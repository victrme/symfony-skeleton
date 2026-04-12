#
# PHP
#

FROM php:8.4.19-fpm AS php

ARG user=www-html
ARG uid=1000

RUN --mount=type=cache,target=/var/cache/apt apt-get update && \
    apt-get install -y --no-install-recommends git unzip libzip-dev libicu-dev && \
    docker-php-ext-install intl pdo_mysql zip opcache && \
    rm -rf /var/lib/apt/lists/*

RUN echo "memory_limit=1G" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN groupadd -g $uid $user && useradd -u $uid -g $user -m -G www-data -d /home/$user $user

WORKDIR /var/www/html

COPY --link --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --link --from=ghcr.io/symfony-cli/symfony-cli:latest /usr/local/bin/symfony /usr/local/bin/symfony

COPY --chown=$user:$user . .
USER $user

#
# Node
#

FROM node:25.8.2 AS node

WORKDIR /var/www/html

USER root

RUN npm install --global pnpm && \
    mkdir -p /home/node/.local/share/pnpm/store && \
    chown -R node:node /home/node/.local

USER node
