FROM php:5-apache

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb