FROM php:8.3-cli as base

# Install system dependencies
RUN set -x && apt-get update && apt-get -y install \
    bash \
    curl \
    unzip \
    zip

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
        opcache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set up project code within container
COPY . /var/www/app
WORKDIR /var/www/app

# Install code coverage driver
RUN pecl install -f pcov && docker-php-ext-enable pcov

# Run composer install to set up PHP dependencies
RUN composer install --optimize-autoloader
RUN composer dump-autoload

ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"
