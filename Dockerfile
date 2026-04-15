# ============================================================
# Dockerfile — Laravel production (Render.com)
# Chemin : à la racine du projet (même niveau que composer.json)
# ============================================================

# ── Étape 1 : build des assets frontend (Node + Vite) ───────
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copier uniquement les fichiers nécessaires à npm
COPY package.json package-lock.json* ./
RUN npm ci

# Copier les sources frontend
COPY resources/ resources/
COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Compiler les assets (génère public/build/)
RUN npm run build


# ── Étape 2 : image PHP finale ───────────────────────────────
FROM php:8.3-fpm-alpine

# Variables d'environnement internes
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# ── Dépendances système ──────────────────────────────────────
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

# ── Composer ─────────────────────────────────────────────────
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ── Répertoire de travail ─────────────────────────────────────
WORKDIR /var/www/html

# ── Dépendances PHP (production uniquement) ───────────────────
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-interaction

# ── Code source ───────────────────────────────────────────────
COPY . .

# ── Assets compilés depuis l'étape frontend ───────────────────
COPY --from=frontend-builder /app/public/build public/build

# ── Permissions ───────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ── Configuration Nginx ───────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# ── Configuration PHP-FPM ─────────────────────────────────────
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ── Configuration PHP (opcache production) ────────────────────
COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# ── Supervisor (gère Nginx + PHP-FPM ensemble) ────────────────
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ── Script de démarrage ───────────────────────────────────────
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Port exposé (Render détecte le port 10000 par défaut)
EXPOSE 10000

CMD ["/start.sh"]
