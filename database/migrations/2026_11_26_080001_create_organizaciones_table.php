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
        Schema::create('organizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 30)->unique();
            $table->string('nombre', 150);
            $table->string('telefono', 20);
            $table->string('correo', 150);
            $table->string('direccion', 255);
            $table->string('licencia_codigo', 100)->unique();
            $table->date('licencia_vencimiento');
            $table->string('logo_url', 255)->nullable();
            $table->enum('estado', ['activa', 'suspendida', 'inactiva'])->default('activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizaciones');
    }
};
