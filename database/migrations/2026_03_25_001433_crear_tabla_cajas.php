<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Quién abrió la caja
            $table->datetime('fecha_apertura');
            $table->datetime('fecha_cierre')->nullable();
            $table->decimal('monto_apertura', 15, 0);
            $table->decimal('monto_cierre', 15, 0)->nullable();
            $table->decimal('monto_real_en_caja', 15, 0)->nullable(); // Lo que el empleado contó
            $table->decimal('diferencia', 15, 0)->nullable(); // Si sobró o faltó plata
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
