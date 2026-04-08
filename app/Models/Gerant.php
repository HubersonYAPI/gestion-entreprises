<?php

namespace App\Models;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Gerant extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'nom',
        'prenoms',
        'contact',
        'piece_identite',
        'user_id',
    ];

    //Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relation avec Entreprise
    public function entreprises()
    {
        return $this->hasMany(Entreprise::class);
    }
}