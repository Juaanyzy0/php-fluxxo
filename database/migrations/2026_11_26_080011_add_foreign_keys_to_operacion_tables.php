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
        Schema::table('turnos', function (Blueprint $table) {
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('usuario_staff_id')->references('id')->on('usuarios_staff')->nullOnDelete();
            $table->foreign('taquilla_id')->references('id')->on('taquillas')->nullOnDelete();
            $table->foreign('regla_prioridad_id')->references('id')->on('reglas_prioridad')->nullOnDelete();
        });

        Schema::table('confirmaciones_asistencia', function (Blueprint $table) {
            $table->foreign('turno_id')->references('id')->on('turnos')->cascadeOnDelete();
        });

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->foreign('turno_id')->references('id')->on('turnos')->cascadeOnDelete();
        });

        Schema::table('registros_historicos', function (Blueprint $table) {
            $table->foreign('turno_id')->references('id')->on('turnos')->nullOnDelete();
            $table->foreign('usuario_staff_id')->references('id')->on('usuarios_staff')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_historicos', function (Blueprint $table) {
            $table->dropForeign(['turno_id']);
            $table->dropForeign(['usuario_staff_id']);
        });

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropForeign(['turno_id']);
        });

        Schema::table('confirmaciones_asistencia', function (Blueprint $table) {
            $table->dropForeign(['turno_id']);
        });

        Schema::table('turnos', function (Blueprint $table) {
            $table->dropForeign(['servicio_id']);
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['usuario_staff_id']);
            $table->dropForeign(['taquilla_id']);
            $table->dropForeign(['regla_prioridad_id']);
        });
    }
};
