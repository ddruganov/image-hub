FROM php:8.0-fpm-alpine

RUN apk add $PHPIZE_DEPS postgresql-dev imagemagick-dev imagemagick libtool
RUN docker-php-ext-install pdo pdo_pgsql
RUN pecl install imagick
RUN docker-php-ext-enable imagick

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

WORKDIR /app
