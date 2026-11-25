<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zona extends Model
{
    protected $table = 'zonas';

    protected $fillable = [
        'nombre',
    ];

    public function comunidades(): HasMany
    {
        return $this->hasMany(Comunidad::class);
    }
}
