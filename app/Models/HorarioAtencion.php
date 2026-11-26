<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorarioAtencion extends Model
{
    use HasFactory;

    protected $table = 'horarios_atencion';

    protected $fillable = [
        'servicio_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'cupos_por_franja',
        'fecha_especifica',
        'es_especial',
        'estado',
    ];

    protected $casts = [
        'servicio_id' => 'integer',
        'cupos_por_franja' => 'integer',
        'fecha_especifica' => 'date',
        'es_especial' => 'boolean',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
