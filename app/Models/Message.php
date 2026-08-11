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

    public function isSupport(): bool
    {
        return $this->alerte_id === null;
    }

    public function alerte()
    {
        return $this->belongsTo(Alerte::class);
    }

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }
}
