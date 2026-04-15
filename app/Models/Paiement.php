<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'declaration_id',
        'montant',
        'reference',
        'statut',
        'date_paiement',
    ];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}