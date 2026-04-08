<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use HasFactory; 
    
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
}
