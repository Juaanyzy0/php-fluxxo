<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroHistorico extends Model
{
    use HasFactory;

    protected $table = 'registros_historicos';

    protected $fillable = [
        'turno_id',
        'usuario_staff_id',
        'fecha',
        'accion',
        'descripcion',
        'resultado',
    ];

    protected $casts = [
        'turno_id' => 'integer',
        'usuario_staff_id' => 'integer',
        'fecha' => 'datetime',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }

    public function usuarioStaff(): BelongsTo
    {
        return $this->belongsTo(UsuarioStaff::class, 'usuario_staff_id');
    }
}
