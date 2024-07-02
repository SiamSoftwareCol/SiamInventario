<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Costo extends Model
{
    use HasFactory;


        public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

        public function item(): BelongsTo
    {
        return $this->BelongsTo(Item::class);
    }

}
