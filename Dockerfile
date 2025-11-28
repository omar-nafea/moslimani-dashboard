# Dockerfile (Laravel - PHP 8.4 FPM)
FROM php:8.4-fpm AS base

# Install system deps
RUN apt-get update && apt-get install -y \
  git \
  curl \
  unzip \
  zip \
  libpq-dev \
  libicu-dev \
  libzip-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libfreetype6-dev \
  libxml2-dev \
  libonig-dev \
  gnupg \
  ca-certificates \
  && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure intl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
  pdo pdo_pgsql \
  intl \
  zip \
  gd \
  bcmath \
  mbstring \
  exif \
  pcntl \
  xml \
  && pecl install redis && docker-php-ext-enable redis

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create app directory
WORKDIR /var/www/html

# Copy composer* first to leverage cache
COPY laravel-backend/composer.json laravel-backend/composer.lock ./

# Install composer dependencies (no dev for production; dev in local)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist

# Copy app
COPY laravel-backend/ ./

# Run laravel specific composer scripts (post-autoload etc)
RUN composer run-script post-autoload-dump || true

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R 775 storage bootstrap/cache

# Build stage finished
FROM base AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

# Expose php-fpm port (internal)
EXPOSE 9000

CMD ["php-fpm"]

