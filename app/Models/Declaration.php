<?php

namespace App\Models;

use App\Models\Document;
use App\Models\Paiement;
use App\Models\Attestation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory; 

    protected $fillable = [
        'entreprise_id',
        'reference',
        'statut',
        'phase',
        'nature_activite',
        'secteur_activite',
        'produits',
        'effectifs',
        'submitted_at',
        'validated_at',
        'date_limite_paiement',
        'paid_at',
        'processed_at',
        'completed_at',
    ];

    //Nom de chaque phase
    public function getPhaseLabelAttribute()
    {
        return match($this->phase){
            1 => 'Création',
            2 => 'Soumission',
            3 => 'Paiement',
            4 => 'En_traitement', 
            5 => 'Terminé',
            default => 'Inconnu',
        };
    }

    //Relation avec Entreprise
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    public function attestation()
    {
        return $this->hasOne(Attestation::class);
    }
}
