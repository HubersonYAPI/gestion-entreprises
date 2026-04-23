#!/bin/sh
# ============================================================
# docker/start.sh — VERSION CORRIGÉE
# Ordre : cache AVANT migrations (pour que config soit lisible)
# ============================================================

set -e

echo "========================================"
echo " Démarrage PDAI — $(date)"
echo "========================================"

# ── 1. Lien symbolique storage ───────────────────────────────
echo "[1/8] Lien symbolique storage..."
php artisan storage:link --force 2>/dev/null || true

# ── 2. Attendre PostgreSQL ───────────────────────────────────
echo "[2/8] Attente de PostgreSQL..."
RETRIES=30
COUNT=0
until php artisan db:show --quiet 2>/dev/null; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $RETRIES ]; then
        echo "  ✗ PostgreSQL non joignable après ${RETRIES} tentatives. Abandon."
        exit 1
    fi
    echo "  ↻ Tentative $COUNT/$RETRIES dans 5s..."
    sleep 5
done
echo "  ✓ PostgreSQL connecté"

# ── 3. Migrations ─────────────────────────────────────────────
# --force  : confirme l'exécution en production
# --isolated : (Laravel 12+) évite le prompt interactif
echo "[3/8] Migrations..."
php artisan migrate --force --isolated 2>/dev/null \
    || php artisan migrate --force
echo "  ✓ Migrations terminées"

# # ── . Seeders ────────────────────────────────────────────────
# echo "[5/7] Seeders..."
 
# # Seeder des rôles Spatie (le plus important)
# echo "  → RoleSeeder..."
# if php artisan db:seed --class=RoleSeeder --force; then
#     echo "  ✓ RoleSeeder OK"
# else
#     echo "  ✗ RoleSeeder ERREUR — vérifier les logs ci-dessus"
#     # On ne bloque pas le démarrage pour les seeders
# fi
 
# echo "  → DatabaseSeeder..."
# if php artisan db:seed --class=DatabaseSeeder --force; then
#     echo "  ✓ DatabaseSeeder OK"
# else
#     echo "  ✗ DatabaseSeeder ERREUR — vérifier les logs ci-dessus"
# fi

# ── 4. Nettoyage COMPLET des caches d'abord ──────────────────
# Important : faire AVANT config:cache pour repartir proprement
echo "[4/8] Nettoyage des caches..."
php artisan view:clear   --quiet
php artisan cache:clear  --quiet
php artisan config:clear --quiet
php artisan route:clear  --quiet
php artisan optimize:clear --quiet
php artisan event:clear  --quiet 2>/dev/null || true

# ── 5. Optimisation production ────────────────────────────────
echo "[5/8] Optimisation production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true

# ── 6. Permissions ────────────────────────────────────────────
echo "[6/8] Permissions..."
chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache
chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# ── 7. Vérification finale ────────────────────────────────────
echo "[7/8] Vérification..."
echo "  APP_URL     = ${APP_URL}"
echo "  DB_HOST     = ${DB_HOST}"
echo "  DB_DATABASE = ${DB_DATABASE}"
echo "  APP_ENV     = ${APP_ENV}"
echo "  APP_DEBUG   = ${APP_DEBUG}"

# ── 8. Lancer les serveurs ────────────────────────────────────
echo "[8/8] Lancement Nginx + PHP-FPM via Supervisor..."
echo "========================================"
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf