FROM php:5-apache

RUN pecl install mongodb 

RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

RUN ln -s /var/www/html /var/www/public

RUN apt-get update && apt-get install -y \
    libzip-dev

RUN docker-php-ext-install zip

RUN apt-get update && apt-get install -y git