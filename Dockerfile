FROM node:15 as react
WORKDIR /usr/src/app
COPY package.json yarn.lock ./
RUN yarn
COPY . ./
RUN yarn build

# FROM nginx:1.12-alpine
# COPY --from=react /usr/src/app/build /usr/share/nginx/html
# EXPOSE 80
# CMD ["nginx", "-g", "daemon off;"]

# PHP / WEB
FROM php:7.4-apache

COPY --from=react /usr/src/app/build /app
COPY --from=react /usr/src/app/api /app/api

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY conf/apache.conf /etc/apache2/conf-available/z-app.conf
RUN a2enconf z-app

RUN apt-get update -qq && \
    apt-get install -qy \
    git \
    gnupg \
    unzip \
    zip \
    zlib1g-dev \
    libpng-dev \
    libzip-dev
RUN docker-php-ext-install -j$(nproc) opcache gd zip
COPY conf/php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /app/api
RUN composer update
#
