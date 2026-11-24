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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id')->index();
            $table->string('tipo', 50);
            $table->enum('canal', ['sms', 'correo', 'whatsapp', 'push']);
            $table->text('mensaje');
            $table->timestamp('fecha_envio')->nullable();
            $table->enum('estado_envio', ['pendiente', 'enviado', 'fallido'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
