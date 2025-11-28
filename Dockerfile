FROM php:8.4-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
  git \
  unzip \
  zip \
  libpq-dev \
  libicu-dev \
  libzip-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libfreetype6-dev \
  libxml2-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && docker-php-ext-install zip \
  && docker-php-ext-install pdo pdo_pgsql \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gd \
  && docker-php-ext-install bcmath mbstring exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php-fpm"]

