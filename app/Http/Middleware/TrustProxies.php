<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

/**
 * TrustProxies — CORRECTIF CRITIQUE pour Render.com
 *
 * Render place votre app derrière un reverse proxy.
 * Sans ce correctif :
 *   - Les redirections HTTP → HTTPS échouent (boucles)
 *   - Les cookies de session sont rejetés (flag "secure" manquant)
 *   - Laravel voit toujours l'IP du proxy, jamais l'IP réelle
 *   - Les URLs générées sont en http:// au lieu de https://
 *
 * Solution : faire confiance à TOUS les proxies (wildcard '*')
 * car Render change ses IPs dynamiquement.
 */
class TrustProxies extends Middleware
{
    /**
     * '*' = faire confiance à tous les proxies.
     * Nécessaire sur Render car les IPs du proxy varient.
     */
    protected $proxies = '*';

    /**
     * Headers transmis par le proxy Render.
     * HEADER_X_FORWARDED_FOR     → IP réelle du visiteur
     * HEADER_X_FORWARDED_HOST    → Nom de domaine réel
     * HEADER_X_FORWARDED_PORT    → Port réel
     * HEADER_X_FORWARDED_PROTO   → "https" (essentiel pour les redirections)
     * HEADER_X_FORWARDED_PREFIX  → Préfixe de chemin
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR     |
        Request::HEADER_X_FORWARDED_HOST    |
        Request::HEADER_X_FORWARDED_PORT    |
        Request::HEADER_X_FORWARDED_PROTO   |
        Request::HEADER_X_FORWARDED_PREFIX;
}
