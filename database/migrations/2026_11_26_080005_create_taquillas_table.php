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
        Schema::create('taquillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dependencia_id')->constrained('dependencias')->cascadeOnDelete();
            $table->foreignId('usuario_staff_id')->nullable()->constrained('usuarios_staff')->nullOnDelete();
            $table->string('codigo', 30);
            $table->string('nombre', 100);
            $table->enum('estado', ['disponible', 'ocupada', 'pausada', 'inactiva'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taquillas');
    }
};
