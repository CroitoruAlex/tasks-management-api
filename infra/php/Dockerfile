FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

# Try installing dependencies (ignore failure if composer.json missing)
#RUN composer install --no-dev --optimize-autoloader || true

# Permissions
#RUN chown -R www-data:www-data /var/www/html \
#    && chmod -R 755 /var/www/html

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

