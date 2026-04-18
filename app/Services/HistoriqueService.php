<?php

namespace App\Services;

use App\Models\Declaration;
use App\Models\DeclarationHistorique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HistoriqueService
 *
 * Service centralisé pour enregistrer toutes les actions
 * effectuées sur une déclaration.
 *
 * Usage dans un contrôleur :
 *   HistoriqueService::enregistrer($declaration, 'valide', $request, 'Tous les documents conformes.');
 */
class HistoriqueService
{
    /**
     * Enregistre une action dans l'historique
     *
     * @param Declaration $declaration  La déclaration concernée
     * @param string      $action       Code de l'action (valide, rejete, etc.)
     * @param Request|null $request     Requête HTTP pour capturer IP/UA
     * @param string|null  $commentaire Commentaire ou motif
     * @param string|null  $ancienStatut Si null, utilise le statut actuel de la déclaration
     */
    public static function enregistrer(
        Declaration $declaration,
        string      $action,
        ?Request    $request     = null,
        ?string     $commentaire = null,
        ?string     $ancienStatut = null
    ): DeclarationHistorique
    {
        return DeclarationHistorique::create([
            'declaration_id' => $declaration->id,
            'user_id'        => Auth::id(),
            'action'         => $action,
            'ancien_statut'  => $ancienStatut ?? $declaration->getOriginal('statut'),
            'nouveau_statut' => $declaration->statut,
            'commentaire'    => $commentaire,
            'ip_adress'      => $request?->ip(),
            'user_agent'     => $request?->userAgent(),
        ]);
    }
}
