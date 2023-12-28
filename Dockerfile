FROM php:8.2-fpm-alpine as symfony_php

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions; \
    install-php-extensions mysqli mysqlnd pdo pdo_mysql zip

COPY . /var/www
WORKDIR /var/www
CMD [ "php-fpm"]
