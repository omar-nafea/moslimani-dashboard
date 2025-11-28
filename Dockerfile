# Production Dockerfile for Moslimani Platform
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
  git \
  curl \
  libpng-dev \
  libxml2-dev \
  zip \
  unzip \
  postgresql-dev \
  freetype-dev \
  libjpeg-turbo-dev \
  oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html/storage \
  && chmod -R 755 /var/www/html/bootstrap/cache

# Create storage directories
RUN mkdir -p storage/app/public \
  && mkdir -p storage/framework/cache \
  && mkdir -p storage/framework/sessions \
  && mkdir -p storage/framework/views \
  && mkdir -p storage/logs \
  && mkdir -p storage/fonts

# Expose port
EXPOSE 8000

# Start command
CMD php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache && \
  php artisan migrate --force && \
  php artisan storage:link && \
  php artisan serve --host=0.0.0.0 --port=8000



