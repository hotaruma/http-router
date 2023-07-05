FROM php:8.2-cli-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add --no-cache $PHPIZE_DEPS linux-headers git \
    && pecl install xdebug-3.2.1 \
	&& docker-php-ext-enable xdebug

COPY docker-php-ext-xdebug.ini "${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini"

WORKDIR /app
