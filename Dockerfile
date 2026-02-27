FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libxml2-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring xml bcmath fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

RUN chmod -R 775 storage bootstrap/cache

RUN php artisan config:clear || true \
    && php artisan route:clear || true \
    && php artisan view:clear || true

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
