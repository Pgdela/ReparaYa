<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'precio_base',
        'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function tecnicos(): HasMany
    {
        return $this->hasMany(Tecnico::class);
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    public function comisiones(): HasMany
    {
        return $this->hasMany(Comision::class);
    }
}
