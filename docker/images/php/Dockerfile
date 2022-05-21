FROM php:8.1.6-fpm

RUN apt-get update && apt-get install -y git

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip