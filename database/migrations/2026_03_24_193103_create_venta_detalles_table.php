<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            
            // Campos para Polimorfismo (puede ser App\Models\Producto o App\Models\Servicio)
            $table->unsignedBigInteger('item_id');
            $table->string('item_type'); 
            
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 15, 2); // Precio al que se vendió en ese momento
            $table->decimal('subtotal', 15, 2);
            
            // Vital para Paraguay / SIFEN
            $table->enum('tasa_iva', ['0', '5', '10'])->default('10');
            $table->decimal('monto_iva', 15, 2); // El cálculo del IVA sobre el subtotal
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('venta_detalles');
    }
};
