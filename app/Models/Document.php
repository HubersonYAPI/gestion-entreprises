<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory; 
    
    protected $fillable = [
        'declaration_id',
        'type',
        'file_path',
        'statut',
        'commentaire',
    ];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}
