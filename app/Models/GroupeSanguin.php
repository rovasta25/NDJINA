<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupeSanguin extends Model
{
    protected $fillable = ['libelle'];

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
}
