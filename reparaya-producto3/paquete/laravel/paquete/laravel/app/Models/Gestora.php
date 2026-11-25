<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gestora extends Model
{
    protected $table = 'gestoras';

    protected $fillable = [
        'user_id',
        'nombre',
        'cif',
        'email',
        'telefono',
        'direccion',
        'porcentaje_comision',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje_comision' => 'decimal:2',
            'activa' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comunidades(): HasMany
    {
        return $this->hasMany(Comunidad::class);
    }

    public function comisiones(): HasMany
    {
        return $this->hasMany(Comision::class);
    }
}
