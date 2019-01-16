FROM composer:latest as composer-dev

ADD composer.json ./composer.json
ADD composer.lock ./composer.lock
ADD vendor vendor

RUN composer install --no-interaction --no-progress --no-suggest

FROM php:7.2-cli-alpine as php-dev

WORKDIR /www-data

COPY --from=composer-dev /app .
ADD src src

ADD phpstan.neon.dist ./phpstan.neon.dist
RUN vendor/bin/phpstan analyse

FROM composer:latest as composer-prod

COPY --from=composer-dev /app .

RUN composer install --no-interaction --no-progress --no-suggest --no-dev --optimize-autoloader


FROM php:7.2-cli-alpine as php-prod

WORKDIR /www-data

COPY --from=composer-prod /app .
ADD src src
