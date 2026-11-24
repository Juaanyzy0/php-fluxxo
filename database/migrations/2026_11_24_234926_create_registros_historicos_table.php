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
        Schema::create('registros_historicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turno_id')->nullable()->index();
            $table->unsignedBigInteger('usuario_staff_id')->nullable()->index();
            $table->timestamp('fecha')->useCurrent();
            $table->string('accion', 100);
            $table->text('descripcion')->nullable();
            $table->string('resultado', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_historicos');
    }
};
