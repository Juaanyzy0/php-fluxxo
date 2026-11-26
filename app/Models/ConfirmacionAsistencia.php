<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfirmacionAsistencia extends Model
{
    use HasFactory;

    protected $table = 'confirmaciones_asistencia';

    protected $fillable = [
        'turno_id',
        'confirmado',
        'fecha_confirmacion',
        'metodo_confirmacion',
        'token_checkin',
        'hora_checkin',
        'observacion',
    ];

    protected $casts = [
        'turno_id' => 'integer',
        'confirmado' => 'boolean',
        'fecha_confirmacion' => 'datetime',
        'hora_checkin' => 'datetime',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }
}
