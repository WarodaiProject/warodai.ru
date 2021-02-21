FROM php:7.4-apache

RUN pecl install mongodb 
RUN pecl install php7.4-cli 

RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

RUN ln -s /var/www/html /var/www/public

RUN apt-get update && apt-get install -y \
    libzip-dev

RUN docker-php-ext-install zip

RUN apt-get update && apt-get install -y git