#!/bin/sh

set -e

echo "========================================"
echo " Démarrage de Ges_Decl sur Render"
echo "========================================"

# 1. Storage link
echo "[1/8] Lien symbolique storage..."
php artisan storage:link --force || true

# 2. Attendre PostgreSQL
echo "[2/8] Attente de la base de données..."
until php artisan db:show --quiet 2>/dev/null; do
    echo "  ↻ PostgreSQL pas encore prêt, nouvelle tentative dans 3s..."
    sleep 3
done
echo "  ✓ PostgreSQL connecté"

# 3. Migrations
echo "[3/8] Migrations..."
php artisan migrate --force --no-interaction

# 4. Seeders
echo "[4/8] Seeders..."
php artisan db:seed --force --no-interaction || echo "  ↻ Seed déjà exécuté"

# 5. Nettoyage cache (MAINTENANT c’est safe)
echo "[5/8] Nettoyage des caches..."
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan optimize:clear

# 6. Optimisation
echo "[6/8] Optimisation production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Permissions
echo "[7/8] Permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Start services
echo "[8/8] Lancement des serveurs..."
echo "========================================"
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf