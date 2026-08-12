<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $fillable = [
        'user_id',
        'groupe_sanguin_id',
        'quantite',
        'urgence',
        'lieu',
        'ville',
        'description',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groupeSanguin()
    {
        return $this->belongsTo(GroupeSanguin::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    public function urgenceLabel(): string
    {
        return match ($this->urgence) {
            'haute' => 'Urgent',
            'moyenne' => 'Modéré',
            default => 'Faible',
        };
    }
}
