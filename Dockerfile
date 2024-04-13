ARG PHP_VERSION

# https://stackoverflow.com/a/69948610/14378620
FROM alpine/git:latest
WORKDIR /clone-workspace
RUN git clone https://github.com/egibide-ciberseguridad/blog.git laravel-blog

# https://shouts.dev/dockerize-a-laravel-app-with-apache-mariadb#step1
FROM php:${PHP_VERSION}-apache

ARG MYSQL_DATABASE
ARG MYSQL_USER
ARG MYSQL_PASSWORD

USER root

WORKDIR /var/www/html

RUN apt update && apt install -y \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache \
    && docker-php-ext-install zip \
    && docker-php-source delete

# Instalar NodeJS: https://www.linuxcapable.com/how-to-install-node-js-14-lts-16-npm-on-debian-11-bullseye/#Install_NodeJS_14_LTS_from_NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=0 /clone-workspace/laravel-blog/ /var/www/html

RUN chown -R www-data:www-data /var/www/html && a2enmod rewrite

USER www-data

# Inicializar la aplicación de Laravel
RUN composer install --no-dev

RUN cp .env.example .env

RUN php artisan key:generate

RUN sed -i 's/^DB_HOST=.*$/DB_HOST=mariadb/g' .env
RUN sed -i "s/^DB_DATABASE=.*$/DB_DATABASE=${MYSQL_DATABASE}/g" .env
RUN sed -i "s/^DB_USERNAME=.*$/DB_USERNAME=${MYSQL_USER}/g" .env
RUN sed -i "s/^DB_PASSWORD=.*$/DB_PASSWORD=${MYSQL_PASSWORD}/g" .env

#RUN sed -i 's|\\URL::forceScheme|//\\URL::forceScheme|g' app/Providers/AppServiceProvider.php

USER root

RUN mkdir -p /var/www/.npm && chown -R www-data:www-data "/var/www/.npm"

USER www-data

RUN npm install
RUN npm run build

#RUN php artisan migrate:fresh --seed

USER root
