<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comunidad extends Model
{
    protected $table = 'comunidades';

    protected $fillable = [
        'gestora_id',
        'zona_id',
        'nombre',
        'direccion',
    ];

    public function gestora(): BelongsTo
    {
        return $this->belongsTo(Gestora::class);
    }

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class);
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }
}
