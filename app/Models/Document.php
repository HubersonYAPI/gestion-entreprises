<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, LogsActivity; 
    
    protected $fillable = [
        'declaration_id',
        'type',
        'file_path',
        'statut',
        'commentaire',
    ];

    // 🔥 Synchronisation automatique
    protected $touches = ['declaration'];

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
