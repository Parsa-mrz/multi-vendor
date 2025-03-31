# Stage 1: Build assets with Node.js
FROM node:18 AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP & Laravel Setup
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
  libpq-dev git unzip curl libicu-dev zlib1g-dev libzip-dev netcat-openbsd \
  && docker-php-ext-install pdo_pgsql intl zip \
  && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs \
  && pecl install redis \
  && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# Copy production .env
COPY .env /var/www/html/.env

# Copy application files and set ownership
COPY . /var/www/html
COPY --from=node-builder --chown=www-data:www-data /app/public /var/www/html/public
RUN git config --global --add safe.directory /var/www/html \
  && chown -R www-data:www-data /var/www/html

# Install Composer dependencies as www-data
USER www-data
RUN composer install --no-dev --optimize-autoloader

# Final setup as root
USER root
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
  && chmod -R 755 /var/www/html/storage \
  && php artisan config:cache \
  && php artisan route:cache

COPY php/php.ini /usr/local/etc/php/php.ini

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]