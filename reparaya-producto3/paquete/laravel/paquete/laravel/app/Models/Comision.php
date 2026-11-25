<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comision extends Model
{
    protected $table = 'comisions';

    protected $fillable = [
        'gestora_id',
        'incidencia_id',
        'servicio_id',
        'precio_base',
        'porcentaje_comision',
        'importe_comision',
        'fecha_generacion',
    ];

    protected function casts(): array
    {
        return [
            'precio_base' => 'decimal:2',
            'porcentaje_comision' => 'decimal:2',
            'importe_comision' => 'decimal:2',
            'fecha_generacion' => 'datetime',
        ];
    }

    public function gestora(): BelongsTo
    {
        return $this->belongsTo(Gestora::class);
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(Incidencia::class);
    }
}
