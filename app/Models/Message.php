<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'alerte_id',
        'expediteur_id',
        'destinataire_id',
        'objet',
        'contenu',
        'lu',
        'traite',
    ];

    protected function casts(): array
    {
        return [
            'lu' => 'boolean',
            'traite' => 'boolean',
        ];
    }

    public function alerte()
    {
        return $this->belongsTo(Alerte::class);
    }

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
