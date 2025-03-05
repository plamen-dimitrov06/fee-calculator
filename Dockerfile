FROM php:7.4-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/fee-calculator
WORKDIR /usr/src/fee-calculator

# this is required for composer/pcre
RUN apt-get update && apt-get install -y libzip-dev \
    && docker-php-ext-install zip

RUN composer install