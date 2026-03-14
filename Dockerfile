FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

FROM php:8.3-cli-alpine

WORKDIR /var/www/html

# install dependencies + mariadb
RUN apk add --no-cache \
    mariadb \
    mariadb-client \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring intl zip gd

COPY . .

COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# prepare laravel folders
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# initialize database
RUN mysql_install_db --user=root --datadir=/var/lib/mysql

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]