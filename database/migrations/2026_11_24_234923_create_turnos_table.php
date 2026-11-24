<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('servicio_id')->index();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->unsignedBigInteger('usuario_staff_id')->nullable()->index();
            $table->unsignedBigInteger('taquilla_id')->nullable()->index();
            $table->unsignedBigInteger('regla_prioridad_id')->nullable()->index();
            $table->string('codigo_turno', 20);
            $table->enum('origen_solicitud', ['remoto', 'walk_in'])->default('remoto');
            $table->integer('puntaje_prioridad')->default(0);
            $table->integer('posicion_cola')->nullable();
            $table->enum('estado', [
                'reservado',
                'confirmado',
                'en_espera',
                'llamado',
                'en_atencion',
                'finalizado',
                'cancelado',
                'ausente',
            ])->default('reservado');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_llamado')->nullable();
            $table->timestamp('fecha_inicio_atencion')->nullable();
            $table->timestamp('fecha_fin_atencion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
