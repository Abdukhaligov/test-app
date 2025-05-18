FROM php:8.2-apache

LABEL maintainer="Hikmat Abdukhaligov"

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git libpq-dev curl \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Configure Apache
RUN a2enmod rewrite && \
    sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
