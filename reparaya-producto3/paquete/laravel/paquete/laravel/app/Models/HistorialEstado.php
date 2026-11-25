<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';

    public $timestamps = false;

    protected $fillable = [
        'incidencia_id',
        'estado_anterior',
        'estado_nuevo',
        'usuario_id',
        'fecha_cambio',
        'observacion',
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(Incidencia::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
