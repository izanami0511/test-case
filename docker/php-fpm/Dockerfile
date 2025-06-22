FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl;

RUN docker-php-ext-install pdo pdo_mysql zip
RUN apt-get update && apt-get install -y unzip

RUN useradd -d /home/develop -m -s /bin/bash develop && echo "develop:develop" | chpasswd && adduser develop sudo

WORKDIR /project

RUN chown -R develop:develop /project

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get -y update
RUN apt-get -y install git

RUN apt-get update && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /project

USER develop
