FROM php:7.2-cli

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    mysql-client

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install pdo_mysql zip
