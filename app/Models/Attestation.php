<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    protected $fillable = [
        'declaration_id',
        'file_path',
        'reference',
    ];

    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }
}
