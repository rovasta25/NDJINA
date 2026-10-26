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
        'latitude',
        'longitude',
        'description',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
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

    public function hasPosition(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public static function distanceKm(?float $lat1, ?float $lng1, ?float $lat2, ?float $lng2): ?float
    {
        if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
            return null;
        }

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 6371.0 * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public static function formatDistance(float $km): string
    {
        if ($km < 1) {
            return max(10, (int) round($km * 1000 / 10) * 10).' m';
        }

        return number_format($km, 1, ',', ' ').' km';
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
