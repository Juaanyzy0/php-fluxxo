<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';

    protected $fillable = [
        'servicio_id',
        'cliente_id',
        'usuario_staff_id',
        'taquilla_id',
        'regla_prioridad_id',
        'codigo_turno',
        'origen_solicitud',
        'puntaje_prioridad',
        'posicion_cola',
        'estado',
        'fecha_solicitud',
        'fecha_llamado',
        'fecha_inicio_atencion',
        'fecha_fin_atencion',
    ];

    protected $casts = [
        'servicio_id' => 'integer',
        'cliente_id' => 'integer',
        'usuario_staff_id' => 'integer',
        'taquilla_id' => 'integer',
        'regla_prioridad_id' => 'integer',
        'puntaje_prioridad' => 'integer',
        'posicion_cola' => 'integer',
        'fecha_solicitud' => 'datetime',
        'fecha_llamado' => 'datetime',
        'fecha_inicio_atencion' => 'datetime',
        'fecha_fin_atencion' => 'datetime',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuarioStaff(): BelongsTo
    {
        return $this->belongsTo(UsuarioStaff::class, 'usuario_staff_id');
    }

    public function taquilla(): BelongsTo
    {
        return $this->belongsTo(Taquilla::class, 'taquilla_id');
    }

    public function reglaPrioridad(): BelongsTo
    {
        return $this->belongsTo(ReglaPrioridad::class, 'regla_prioridad_id');
    }

    public function confirmacionAsistencia(): HasOne
    {
        return $this->hasOne(ConfirmacionAsistencia::class, 'turno_id');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'turno_id');
    }

    public function registrosHistoricos(): HasMany
    {
        return $this->hasMany(RegistroHistorico::class, 'turno_id');
    }
}
