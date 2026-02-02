# DocuPet Laravel app - runs on any OS (macOS, Linux, Windows)
FROM php:8.2-cli

# Install system deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring xml zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Node 20 LTS for frontend build
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# App files (vendor/node_modules excluded via .dockerignore)
COPY . .
RUN composer install --no-dev --no-scripts --prefer-dist \
    && composer dump-autoload --optimize \
    && php artisan package:discover --ansi || true
RUN npm ci && npm run build && rm -rf node_modules

RUN chmod +x /var/www/html/docker-entrypoint.sh \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
