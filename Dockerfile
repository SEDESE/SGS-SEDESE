# syntax=docker/dockerfile:1

##############################
# Stage 1 - Vite asset build
##############################
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build


##############################
# Stage 2 - Composer deps
##############################
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader


##############################
# Stage 3 - Runtime (nginx + php-fpm ja embutidos)
##############################
FROM serversideup/php:8.4-fpm-nginx AS runtime

USER root
RUN install-php-extensions pdo_mysql intl bcmath zip

# Automacoes do Laravel rodam no boot do container.
# Como o config:cache roda em runtime, ele enxerga as env vars do CapRover.
ENV AUTORUN_ENABLED=true \
    AUTORUN_LARAVEL_MIGRATION=true \
    AUTORUN_LARAVEL_STORAGE_LINK=true \
    AUTORUN_LARAVEL_CONFIG_CACHE=true \
    AUTORUN_LARAVEL_ROUTE_CACHE=true \
    AUTORUN_LARAVEL_VIEW_CACHE=true \
    PHP_OPCACHE_ENABLE=1

WORKDIR /var/www/html

# App + vendor + assets buildados (tudo pertencendo a www-data)
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

USER www-data

# A imagem ja expoe e serve na porta 8080
EXPOSE 8080