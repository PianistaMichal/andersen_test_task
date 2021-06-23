FROM php:8.0-cli
COPY . /code
WORKDIR /code
CMD ["php", "-a"]