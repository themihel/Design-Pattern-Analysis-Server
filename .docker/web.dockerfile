FROM php:7.2-apache

COPY . /web
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /web
RUN apt-get update
RUN apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite