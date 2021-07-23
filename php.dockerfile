FROM php:8.0.8-fpm

ADD ./php/www.conf /usr/local/etc/php-fpm.d

RUN addgroup -g 1000 spectral && adduser -G spectral -g spectral -s /bin/sh -D spectral

RUN mkdir -p /var/www

RUN chown spectral:spectral /var/www

WORKDIR /var/www

RUN docker-php-ext-install pdo pdo_mysql