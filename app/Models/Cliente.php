<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clientes';

    protected $fillable = [
        'cedula',
        'nombre',
        'telefono',
        'correo',
        'canal_notificacion_preferido',
        'puntaje_reputacion',
        'bloqueado_hasta',
        'estado',
        'fecha_registro',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'puntaje_reputacion' => 'integer',
        'bloqueado_hasta' => 'datetime',
        'fecha_registro' => 'datetime',
    ];

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class, 'cliente_id');
    }
}
