# ============================================================
# Dockerfile — VERSION CORRIGÉE pour Render.com
# ============================================================

# ── Étape 1 : Build frontend (Node + Vite) ───────────────────
FROM node:20-alpine AS frontend-builder

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci --silent

COPY resources/ resources/
COPY vite.config.js ./
# Ces fichiers sont optionnels selon votre config
COPY tailwind.config.js ./
COPY postcss.config.js  ./

RUN npm run build


# ── Étape 2 : Image PHP production ───────────────────────────
FROM php:8.3-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# Dépendances système
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    icu-dev \
    libxml2-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        mbstring \
        exif \
        pcntl \
        bcmath \
        intl \
        opcache \
        xml

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dépendances PHP (production)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction

# Code source complet
COPY . .

# Assets compilés depuis l'étape frontend
COPY --from=frontend-builder /app/public/build public/build

# ── CORRECTIF : s'assurer que storage et bootstrap/cache existent ──
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configs Docker
COPY docker/nginx.conf       /etc/nginx/http.d/default.conf
COPY docker/php-fpm.conf     /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini          /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh         /start.sh
RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]