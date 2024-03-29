FROM php:8.1-cli-alpine as composer-dev
WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /bin/composer
ADD composer.json composer.lock ./

RUN composer install --no-interaction --no-progress --no-suggest

FROM php:8.1-cli-alpine as php-dev

WORKDIR /www-data

COPY --from=composer-dev /app .
ADD src src
ADD tests tests

ADD phpspec.yml.dist ./phpspec.yml.dist
RUN vendor/bin/phpspec run

ADD phpunit.xml.dist ./phpunit.xml.dist
RUN vendor/bin/phpunit

ADD .php_cs.dist.php ./.php_cs.dist.php
RUN PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff --config .php_cs.dist.php

ADD phpstan.neon.dist ./phpstan.neon.dist
RUN php -d memory_limit=-1 vendor/bin/phpstan analyse

FROM php:8.1-cli-alpine as composer-prod
WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /bin/composer
COPY --from=composer-dev /app .

RUN composer install --no-interaction --no-progress --no-suggest --no-dev --optimize-autoloader


FROM php:8.1-cli-alpine as php-prod

WORKDIR /www-data

COPY --from=composer-prod /app .
ADD src src
