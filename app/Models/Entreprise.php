<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use HasFactory, LogsActivity; 
    
    protected $fillable = [
        'nom',
        'rccm',
        'adresse',
        'type_entreprise',
        'secteur_activite',
    ];

    //Relation avec Gerant
    public function gerant()
    {
        return $this->belongsTo(Gerant::class);
    }

    //Relation avec Declaration
    public function declarations()
    {
        return $this->hasMany(Declaration::class);
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
