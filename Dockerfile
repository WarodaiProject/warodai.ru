FROM php:5-apache

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN apt-get update && apt-get install -y git