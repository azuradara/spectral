FROM composer:2

RUN addgroup -g 1000 spectral && adduser -G spectral -g spectral -s /bin/sh -D spectral

WORKDIR /var/www