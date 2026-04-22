<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory, LogsActivity;
    
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['statut', 'phase'])
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs()
        ->setDescriptionForEvent(fn(string $eventName) => "Declaration {$eventName}");
    }
}