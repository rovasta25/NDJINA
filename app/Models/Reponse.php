<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    protected $fillable = [
        'alerte_id',
        'user_id',
        'message',
        'statut',
    ];

    public function alerte()
    {
        return $this->belongsTo(Alerte::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
