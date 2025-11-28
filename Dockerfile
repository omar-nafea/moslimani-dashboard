# Dockerfile for Railway deployment
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy application files
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize

# Create directories and set permissions
RUN mkdir -p storage/framework/{cache/data,sessions,views} \
    && mkdir -p storage/{logs,app/public,app/mpdf-temp,fonts} \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Cache config (will be re-done at runtime with proper env vars)
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Expose port
EXPOSE ${PORT:-8000}

# Start command
CMD php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link 2>/dev/null || true && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
