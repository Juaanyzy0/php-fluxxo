<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UsuarioStaff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios_staff';

    protected $fillable = [
        'organizacion_id',
        'rol_id',
        'cedula',
        'nombre',
        'email',
        'telefono',
        'password',
        'estado',
        'fecha_registro',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'organizacion_id' => 'integer',
        'rol_id' => 'integer',
        'password' => 'hashed',
        'fecha_registro' => 'datetime',
    ];

    public function hasRole(string $roleName): bool
    {
        return $this->rol?->nombre !== null
            && strcasecmp($this->rol->nombre, $roleName) === 0;
    }

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function taquillas(): HasMany
    {
        return $this->hasMany(Taquilla::class, 'usuario_staff_id');
    }

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class, 'usuario_staff_id');
    }

    public function registrosHistoricos(): HasMany
    {
        return $this->hasMany(RegistroHistorico::class, 'usuario_staff_id');
    }
}
