#!/bin/sh
set -e
echo "Starting PHP-FPM..."
php-fpm -D
echo "Starting nginx..."
nginx -g 'daemon off;'
