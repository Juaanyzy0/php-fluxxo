<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'dependencia_id',
        'nombre',
        'prefijo_turno',
        'descripcion',
        'duracion_min',
        'holgura_min',
        'estado',
    ];

    protected $casts = [
        'dependencia_id' => 'integer',
        'duracion_min' => 'integer',
        'holgura_min' => 'integer',
    ];

    public function dependencia(): BelongsTo
    {
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function horariosAtencion(): HasMany
    {
        return $this->hasMany(HorarioAtencion::class, 'servicio_id');
    }

    public function reglasPrioridad(): BelongsToMany
    {
        return $this->belongsToMany(
            ReglaPrioridad::class,
            'servicio_regla_prioridad',
            'servicio_id',
            'regla_prioridad_id'
        )->withTimestamps();
    }

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class, 'servicio_id');
    }
}
