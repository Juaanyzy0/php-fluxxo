<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taquilla extends Model
{
    use HasFactory;

    protected $table = 'taquillas';

    protected $fillable = [
        'dependencia_id',
        'usuario_staff_id',
        'codigo',
        'nombre',
        'estado',
    ];

    protected $casts = [
        'dependencia_id' => 'integer',
        'usuario_staff_id' => 'integer',
    ];

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function usuarioStaff(): BelongsTo
    {
        return $this->belongsTo(UsuarioStaff::class, 'usuario_staff_id');
    }

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class, 'taquilla_id');
    }
}
