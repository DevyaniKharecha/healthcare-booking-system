# ---------------------------------------------
# Use the official PHP 8.3 FPM image
# ---------------------------------------------
FROM php:8.3-fpm

# ---------------------------------------------
# Set working directory in the container
# ---------------------------------------------
WORKDIR /var/www

# ---------------------------------------------
# Install system dependencies and PHP extensions
# ---------------------------------------------
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    vim \
    libzip-dev \
    mariadb-client \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        zip \
        gd

# ---------------------------------------------
# Install Composer globally
# ---------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ---------------------------------------------
# Copy application source code into the container
# ---------------------------------------------
COPY . .

# ---------------------------------------------
# Install PHP dependencies including Laravel Passport
# ---------------------------------------------
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && composer require laravel/passport

# ---------------------------------------------
# Clear Laravel caches (for a clean build)
# ---------------------------------------------
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan event:clear

# ---------------------------------------------
# Set correct file permissions
# ---------------------------------------------
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# ---------------------------------------------
# Expose PHP-FPM port (default for Nginx reverse proxy)
# ---------------------------------------------
EXPOSE 9000

# ---------------------------------------------
# Start PHP-FPM server
# ---------------------------------------------
CMD ["php-fpm"]
