FROM php:7.3-apache

RUN apt-get update && apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

COPY app/ /var/www/html/

RUN mkdir -p /var/www/html/data && chown -R www-data:www-data /var/www/html/data

# tampilkan error agar mahasiswa bisa melihat exception
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/dev.ini
