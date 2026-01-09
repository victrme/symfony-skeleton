FROM php:8.3.4-fpm AS php

# Add the github script to easy install-php-extensions
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql zip ctype iconv xsl gd intl

# Ajouter composer depuis l'image composer officiel
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ajouter le client symfony
COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony

# Install git
RUN apt-get update \
    && apt-get install -y git \
    && rm -rf /var/lib/apt/lists/*

# Pour linux on se créer un user afin de synchroniser
# les ids et groups ids pour eviter les probleme de droit
RUN groupadd -g 501 devGroup
RUN useradd -u 501 -g 501 -m devUser
USER devUser

FROM node:22 AS node

RUN groupadd -g 501 devGroup
RUN useradd -u 501 -g 501 -m devUser
USER devUser

FROM caddy:2.11 AS caddy

COPY Caddyfile /etc/caddy/Caddyfile
