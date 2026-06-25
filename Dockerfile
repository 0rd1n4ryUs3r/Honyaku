FROM php:8.2-apache

# Salin semua file projek lo ke dalam folder server Apache
COPY . /var/www/html/

# Buka port 80 untuk akses web
EXPOSE 80
