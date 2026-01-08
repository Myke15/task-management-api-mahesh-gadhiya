FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Redis and PCOV for high-performance coverage
RUN pecl install redis pcov && \
    docker-php-ext-enable redis pcov

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Increase PHP memory limit for CLI and FPM
RUN echo "memory_limit=1G" > /usr/local/etc/php/conf.d/memory-limit.ini

WORKDIR /var/www

EXPOSE 9000
CMD ["php-fpm"]