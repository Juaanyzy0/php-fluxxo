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
        Schema::create('confirmaciones_asistencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id')->unique();
            $table->boolean('confirmado')->default(false);
            $table->timestamp('fecha_confirmacion')->nullable();
            $table->string('metodo_confirmacion', 50)->nullable();
            $table->string('token_checkin', 100)->unique()->nullable();
            $table->timestamp('hora_checkin')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmaciones_asistencia');
    }
};
