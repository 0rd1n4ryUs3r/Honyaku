FROM php:8.2-apache

# Mengizinkan Apache mengoper Environment Variable ke PHP
RUN sed -i 's/Variables_Order = .*/Variables_Order = "EGPCS"/' $PHP_INI_DIR/php.ini-development \
    && cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY . /var/www/html/

EXPOSE 80
