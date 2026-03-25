<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta_cobros', function (Blueprint $table) {
            $table->id();
            // Relación con ventas
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            
            $table->string('metodo_pago'); // Efectivo, Tarjeta, Transferencia, Seña
            $table->decimal('monto', 15, 0);
            $table->string('referencia')->nullable(); // Nro de comprobante
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_cobros');
    }
};
