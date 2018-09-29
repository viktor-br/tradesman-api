FROM php:7.2-fpm

RUN apt-get update && apt-get install -y
RUN docker-php-ext-install pdo_mysql

WORKDIR /usr/src/tradesman-api
