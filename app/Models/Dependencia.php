<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencias';

    protected $fillable = [
        'organizacion_id',
        'nombre',
        'codigo',
        'ubicacion',
        'telefono',
        'estado',
    ];

    protected $casts = [
        'organizacion_id' => 'integer',
    ];

    public function organizacion(): BelongsTo
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id');
    }

    public function taquillas(): HasMany
    {
        return $this->hasMany(Taquilla::class, 'dependencia_id');
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'dependencia_id');
    }
}
