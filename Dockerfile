FROM dunglas/frankenphp:1-php8.3-alpine AS frankenphp_base

# PHP extensions
RUN install-php-extensions pdo_pgsql

# Install Composer
COPY --from=composer/composer:2-bin /composer /usr/bin/composer

# Install Node.js and npm
RUN apk add --no-cache nodejs npm git

COPY config/php/php.ini /usr/local/etc/php/conf.d/99-custom.ini

FROM frankenphp_base AS frankenphp_dev

#ENV FRANKENPHP_CONFIG="worker ./public/index.php"
#ENV APP_RUNTIME="Runtime\\FrankenPhpSymfony\\Runtime"

WORKDIR /app

COPY . /app

# Only run composer install if composer.json exists
RUN if [ -f composer.json ]; then composer install --no-scripts --no-autoloader; fi
RUN if [ -f composer.json ]; then composer dump-autoload --optimize; fi
