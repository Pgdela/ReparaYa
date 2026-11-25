<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'user_id',
        'servicio_id',
        'descripcion',
        'direccion',
        'telefono',
        'fecha_preferida',
        'precio_base',
        'estado',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
