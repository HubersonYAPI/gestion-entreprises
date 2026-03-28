<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
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
