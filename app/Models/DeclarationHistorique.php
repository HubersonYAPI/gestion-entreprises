<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeclarationHistorique extends Model
{
    protected $table = 'declaration_historiques';
 
    protected $fillable = [
        'declaration_id',
        'user_id',
        'action',
        'ancien_statut',
        'nouveau_statut',
        'commentaire',
        'ip_adress',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────

    public function declaration():BelongsTo
    {
        return $this->belongsTo(Declaration::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Labels lisibles ───────────────────────────────────────
 
    /**
     * Libellé de l'action pour l'affichage
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'creation'          => 'Création',
            'modification'      => 'Modification',

            // USER
            'soumis'            => 'Soumission',
            'paye'              => 'Paiement effectué',

            // ADMIN
            'valide'            => 'Validation',
            'approuve'          => 'Approuvée',
            'rejete'            => 'Rejet',
            'en_traitement'     => 'Mise en traitement',

            // DOCUMENT
            'document_valide'   => 'Document validé',
            'document_rejete'   => 'Document rejeté',

            default             => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Couleur du badge selon l'action
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'soumis'            => 'blue',
            'paye'              => 'teal',

            'valide'            => 'green',
            'approuve'          => 'green',

            'rejete'            => 'red',

            'en_traitement'     => 'yellow',

            'creation'          => 'gray',
            'modification'      => 'gray',

            'document_valide'   => 'green',
            'document_rejete'   => 'red',

            default             => 'gray',
        };
    }

    // ── Scopes / Filtres ────────────────────────────────────────────────
    public function scopeRecent($query)
    {
        return $query->latest()->limit(50);
    }
 
    public function scopeForDeclaration($query, int $declarationId)
    {
        return $query->where('declaration_id', $declarationId);
    }

}
