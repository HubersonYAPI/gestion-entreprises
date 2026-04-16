#!/bin/sh
# ============================================================
# docker/start.sh — VERSION CORRIGÉE
# Ordre : cache AVANT migrations (pour que config soit lisible)
# ============================================================

set -e

echo "========================================"
echo " Démarrage Ges_Decl — $(date)"
echo "========================================"

# ── 1. Lien symbolique storage ───────────────────────────────
echo "[1/9] Lien symbolique storage..."
php artisan storage:link --force 2>/dev/null || true

# ── 2. Attendre PostgreSQL ───────────────────────────────────
echo "[3/9] Attente de PostgreSQL..."
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
echo "[4/7] Migrations..."
php artisan migrate --force --isolated 2>/dev/null \
    || php artisan migrate --force
echo "  ✓ Migrations terminées"

# # ── 4. Seeders ────────────────────────────────────────────────
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

# ── 5. Nettoyage COMPLET des caches d'abord ──────────────────
# Important : faire AVANT config:cache pour repartir proprement
echo "[2/9] Nettoyage des caches..."
php artisan view:clear   --quiet
php artisan cache:clear  --quiet
php artisan config:clear --quiet
php artisan route:clear  --quiet
php artisan optimize:clear --quiet
php artisan event:clear  --quiet 2>/dev/null || true

# ── 6. Optimisation production ────────────────────────────────
echo "[6/9] Optimisation production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true

# ── 7. Permissions ────────────────────────────────────────────
echo "[7/9] Permissions..."
chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache
chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# ── 8. Vérification finale ────────────────────────────────────
echo "[8/9] Vérification..."
echo "  APP_URL     = ${APP_URL}"
echo "  DB_HOST     = ${DB_HOST}"
echo "  DB_DATABASE = ${DB_DATABASE}"
echo "  APP_ENV     = ${APP_ENV}"
echo "  APP_DEBUG   = ${APP_DEBUG}"

# ── 9. Lancer les serveurs ────────────────────────────────────
echo "[9/9] Lancement Nginx + PHP-FPM via Supervisor..."
echo "========================================"
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf