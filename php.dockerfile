FROM php:8.0.8-fpm

ADD ./php/www.conf /usr/local/etc/php-fpm.d

RUN groupadd -g 1000 spectral
RUN useradd -u 1000 -ms /bin/bash -g spectral spectral

RUN mkdir -p /var/www

RUN chown spectral:spectral /var/www

WORKDIR /var/www

RUN docker-php-ext-install pdo pdo_mysql