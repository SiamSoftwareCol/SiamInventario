<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehiculo extends Model
{
    use HasFactory;

    protected $casts = [
        'ruta_imagen' => 'array',
    ];

        public function fabricante(): BelongsTo
    {
        return $this->BelongsTo(Fabricante::class);
    }


        public function color(): BelongsTo
    {
        return $this->BelongsTo(Color::class);
    }


        public function combustible(): BelongsTo
    {
        return $this->BelongsTo(Combustible::class);
    }


        public function trasmision(): BelongsTo
    {
        return $this->BelongsTo(Trasmision::class);
    }


        public function estado(): BelongsTo
    {
        return $this->BelongsTo(Estado::class);
    }


            public function linea(): BelongsTo
    {
        return $this->BelongsTo(Linea::class);
    }

        public function costos(): HasMany
    {
        return $this->hasMany(Costo::class);
    }
}
