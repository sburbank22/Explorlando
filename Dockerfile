FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

COPY nginx.conf /etc/nginx/http.d/default.conf

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"
