FROM php:8.2-fpm
COPY . /var/www/html/
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug