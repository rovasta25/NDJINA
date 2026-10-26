<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'structure_type',
        'phone',
        'city',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function maskedPhone(): string
    {
        if (! $this->phone) {
            return '—';
        }

        return substr($this->phone, 0, 2).str_repeat('*', max(0, strlen($this->phone) - 4)).substr($this->phone, -2);
    }

    public function isStructure(): bool
    {
        return $this->type === 'structure';
    }

    public function structureTypeLabel(): string
    {
        return match ($this->structure_type) {
            'banque_sang' => 'Banque de sang',
            'hopital' => 'Hôpital',
            'clinique' => 'Clinique',
            'laboratoire' => 'Laboratoire',
            'autre' => 'Autre',
            default => '—',
        };
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }
}
