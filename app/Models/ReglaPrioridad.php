<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReglaPrioridad extends Model
{
    use HasFactory;

    protected $table = 'reglas_prioridad';

    protected $fillable = [
        'organizacion_id',
        'nombre',
        'nivel',
        'peso_prioridad',
        'criterio',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'organizacion_id' => 'integer',
        'nivel' => 'integer',
        'peso_prioridad' => 'integer',
    ];

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }

    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(
            Servicio::class,
            'servicio_regla_prioridad',
            'regla_prioridad_id',
            'servicio_id'
        )->withTimestamps();
    }

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class, 'regla_prioridad_id');
    }
}
