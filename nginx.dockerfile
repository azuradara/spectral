FROM nginx:stable-alpine

COPY ./nginx/nginx.conf /etc/nginx/

COPY ./nginx/default.conf /etc/nginx/conf.d

RUN mkdir -p /var/www

RUN addgroup -g 1000 spectral && adduser -G spectral -g spectral -s /bin/sh -D spectral

RUN chown spectral:spectral /var/www