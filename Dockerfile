FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/

COPY nginx.conf /etc/nginx/http.d/default.conf

RUN chmod -R 755 /var/www/html && find /var/www/html -type f -exec chmod 644 {} \;

RUN printf '#!/bin/sh\nset -e\nphp-fpm -D\nnginx -g "daemon off;"\n' > /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]
