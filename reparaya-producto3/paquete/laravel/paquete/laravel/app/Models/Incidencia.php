<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'localizador',
        'cliente_id',
        'tecnico_id',
        'comunidad_id',
        'servicio_id',
        'descripcion',
        'direccion',
        'telefono_contacto',
        'fecha_servicio',
        'franja_horaria',
        'tipo_urgencia',
        'estado',
        'precio_base',
    ];

    protected $casts = [
        'fecha_servicio' => 'datetime',
        'precio_base' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Tecnico::class, 'tecnico_id');
    }

    public function comunidad(): BelongsTo
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialEstado::class, 'incidencia_id');
    }
}
