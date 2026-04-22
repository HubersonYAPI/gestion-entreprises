<?php

namespace App\Models;

use App\Models\Document;
use App\Models\Paiement;
use App\Models\Attestation;
use App\Models\DeclarationHistorique;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory, LogsActivity; 

    protected $fillable = [
        'entreprise_id',
        'reference',
        'statut',
        'phase',
        'commentaire',        // ← motif de rejet
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

    protected $casts = [
        'date_limite_paiement' => 'datetime',
        'completed_at' => 'datetime',
    ];

    //Nom de chaque phase
    public function getPhaseLabelAttribute()
    {
        return match($this->phase){
            1 => 'Création',
            2 => 'Vérification',
            3 => 'Paiement',
            4 => 'Traitement', 
            5 => 'Finalisation',
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

    public function historiques()
    {
        return $this->hasMany(DeclarationHistorique::class)->with('user')->latest();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['statut', 'phase'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs()
        ->setDescriptionForEvent(fn(string $eventName) => "Declaration {$eventName}");
    }

}
