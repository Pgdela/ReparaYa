<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tecnico(): HasOne
    {
        return $this->hasOne(Tecnico::class);
    }

    public function incidenciasCliente(): HasMany
    {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(HistorialEstado::class, 'usuario_id');
    }
}
