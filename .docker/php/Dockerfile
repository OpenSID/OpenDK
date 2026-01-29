FROM php:8.3-fpm

WORKDIR /var/www

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    curl \
    libonig-dev \
    libzip-dev \
    libwebp-dev \
    gnupg \
    git \
    libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions (Added 'sockets' here)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath intl sockets

# 3. Install Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copy application code
COPY --chown=www:www . /var/www

# We explicitly REMOVE the broken testing tools from composer.json inside the container
# This prevents the version conflict error entirely.
RUN git config --global --add safe.directory /var/www \
    && rm -f config/scribe.php \
    && composer remove --dev pestphp/pest pestphp/pest-plugin-browser pestphp/pest-plugin-laravel --no-update --no-interaction \
    && composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Change user
USER www

EXPOSE 9000
CMD ["php-fpm"]