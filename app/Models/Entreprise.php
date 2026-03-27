<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
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
}
