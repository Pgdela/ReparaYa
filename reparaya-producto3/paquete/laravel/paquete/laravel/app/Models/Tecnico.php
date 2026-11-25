<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tecnico extends Model
{
    protected $table = 'tecnicos';

    protected $fillable = [
        'user_id',
        'nombre_completo',
        'servicio_id',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }
}
