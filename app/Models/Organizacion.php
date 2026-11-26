<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organizacion extends Model
{
    use HasFactory;

    protected $table = 'organizaciones';

    protected $fillable = [
        'nit',
        'nombre',
        'telefono',
        'correo',
        'direccion',
        'licencia_codigo',
        'licencia_vencimiento',
        'logo_url',
        'estado',
    ];

    protected $casts = [
        'licencia_vencimiento' => 'date',
    ];

    public function dependencias(): HasMany
    {
        return $this->hasMany(Dependencia::class, 'organizacion_id');
    }

    public function usuariosStaff(): HasMany
    {
        return $this->hasMany(UsuarioStaff::class, 'organizacion_id');
    }

    public function reglasPrioridad(): HasMany
    {
        return $this->hasMany(ReglaPrioridad::class, 'organizacion_id');
    }
}
