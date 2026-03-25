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
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained()->cascadeOnDelete();

            // Polimórfico (producto o servicio)
            $table->unsignedBigInteger('item_id');
            $table->string('item_type'); // 'producto' o 'servicio'

            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 0);
            $table->decimal('subtotal', 10, 0);

            // Opcional pero recomendado
            $table->decimal('tasa_iva', 5, 2)->nullable();
            $table->decimal('monto_iva', 10, 0)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
    }
};
