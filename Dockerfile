FROM php:8.0-fpm

RUN docker-php-ext-install bcmath
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER 1000
COPY . /code
WORKDIR /code