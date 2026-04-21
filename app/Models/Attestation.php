<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attestation extends Model
{
    use HasFactory;
    
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
