<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->string('nro_factura')->unique();
            $table->enum('estado', ['borrador', 'enviada', 'aceptada', 'rechazada'])->default('borrador');
            $table->text('xml_contenido')->nullable();
            $table->text('respuesta_sifen')->nullable();
            $table->timestamp('emitido_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
