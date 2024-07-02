<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    use HasFactory;

    public function predialacuerdo(): HasMany
    {
        return $this->hasMany(PredialAcuerdo::class);
    }

    public function capacitacion(): HasMany
    {
        return $this->hasMany(Capacitacion::class);
    }
}
