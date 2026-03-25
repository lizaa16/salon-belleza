<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained();
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->decimal('monto', 15, 0);
            $table->string('concepto'); // Ej: "Seña Cita #45", "Venta #10", "Compra de Café"
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'seña_aplicada']);
            
            // Polimorfismo para saber de dónde viene el movimiento
            $table->unsignedBigInteger('referencia_id')->nullable(); 
            $table->string('referencia_type')->nullable(); // App\Models\Venta o App\Models\Cita
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
    }
};
