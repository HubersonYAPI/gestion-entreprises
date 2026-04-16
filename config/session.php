<?php

/*
|--------------------------------------------------------------------------
| config/session.php — CORRECTIF pour Render (HTTPS + reverse proxy)
|--------------------------------------------------------------------------
| Le problème principal de votre erreur 500 est LÀ :
|
| Render sert votre app en HTTPS, mais le conteneur Docker lui
| parle en HTTP interne. Laravel voit du HTTP et génère des cookies
| sans flag "secure". Le navigateur les rejette → session vide →
| middleware auth plante → erreur 500.
|
| Solution : forcer secure=true et faire confiance au proxy.
|--------------------------------------------------------------------------
|
| ⚠️  CE FICHIER REMPLACE ENTIÈREMENT config/session.php
| Vérifiez qu'il correspond à votre version Laravel (12.x)
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Str;

return [

    /*
    |----------------------------------------------------------------------
    | Default Session Driver
    |----------------------------------------------------------------------
    | Vous utilisez "database" → les sessions sont en base PostgreSQL.
    | C'est robuste mais nécessite que la table "sessions" existe.
    | Elle est créée automatiquement par migrate.
    */
    'driver' => env('SESSION_DRIVER', 'database'),

    /*
    |----------------------------------------------------------------------
    | Session Lifetime
    |----------------------------------------------------------------------
    */
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |----------------------------------------------------------------------
    | Session Encryption
    |----------------------------------------------------------------------
    */
    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |----------------------------------------------------------------------
    | Session File Location
    |----------------------------------------------------------------------
    */
    'files' => storage_path('framework/sessions'),

    /*
    |----------------------------------------------------------------------
    | Session Database Connection
    |----------------------------------------------------------------------
    */
    'connection' => env('SESSION_CONNECTION'),

    /*
    |----------------------------------------------------------------------
    | Session Database Table
    |----------------------------------------------------------------------
    */
    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |----------------------------------------------------------------------
    | Session Cache Store
    |----------------------------------------------------------------------
    */
    'store' => env('SESSION_STORE'),

    /*
    |----------------------------------------------------------------------
    | Session Sweeping Lottery
    |----------------------------------------------------------------------
    */
    'lottery' => [2, 100],

    /*
    |----------------------------------------------------------------------
    | Session Cookie Name
    |----------------------------------------------------------------------
    */
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    /*
    |----------------------------------------------------------------------
    | Session Cookie Path
    |----------------------------------------------------------------------
    */
    'path' => env('SESSION_PATH', '/'),

    /*
    |----------------------------------------------------------------------
    | Session Cookie Domain
    |----------------------------------------------------------------------
    | null → le cookie est valide pour le domaine actuel uniquement.
    | Correct pour Render (ges-decl.onrender.com).
    */
    'domain' => env('SESSION_DOMAIN', null),

    /*
    |----------------------------------------------------------------------
    | HTTPS Only Cookies — CORRECTIF CLÉ
    |----------------------------------------------------------------------
    | Doit être TRUE sur Render car le site est servi en HTTPS.
    |
    | Si cette valeur est false sur un site HTTPS :
    |   → Le cookie session n'a pas le flag "Secure"
    |   → Certains navigateurs (Chrome) le bloquent
    |   → L'utilisateur semble déconnecté à chaque page → 500
    |
    | On lit depuis SESSION_SECURE_COOKIE (à mettre à "true" dans Render)
    | avec fallback automatique selon APP_ENV.
    */
    'secure' => env('SESSION_SECURE_COOKIE', env('APP_ENV') === 'production'),

    /*
    |----------------------------------------------------------------------
    | HTTP Access Only
    |----------------------------------------------------------------------
    | true = JavaScript ne peut pas lire le cookie (sécurité XSS)
    */
    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |----------------------------------------------------------------------
    | Same-Site Cookies
    |----------------------------------------------------------------------
    | 'lax' est le bon choix : protège contre CSRF sans bloquer
    | les redirections OAuth/SSO.
    */
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |----------------------------------------------------------------------
    | Partitioned Cookies (CHIPS)
    |----------------------------------------------------------------------
    */
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];