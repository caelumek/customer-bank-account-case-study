FROM php:8.2-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt update \
    && apt upgrade -y \
    && apt install -y \
        curl \
        git \
        wget \
        unzip \
        zip

RUN install-php-extensions \
    apcu \
    pcntl \
    gd \
    intl \
    opcache \
    pdo_mysql \
    xdebug-stable \
    zip

ADD ./conf.d/intl.ini /usr/local/etc/php/conf.d/intl.ini
ADD ./conf.d/memory.ini /usr/local/etc/php/conf.d/memory.ini
ADD ./conf.d/session.ini /usr/local/etc/php/conf.d/session.ini
ADD ./conf.d/timezone.ini /usr/local/etc/php/conf.d/timezone.ini
ADD ./conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ADD ./scripts/xdebug-toggle /usr/local/bin/xdebug-toggle
RUN chmod +x /usr/local/bin/xdebug-toggle
