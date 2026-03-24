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
        Schema::table('ventas', function (Blueprint $table) {
    // Si ya existen, no los agregues, solo los nuevos:
            $table->string('numero_factura')->nullable()->after('id'); // Ej: 001-001-0000001
            $table->decimal('monto_seña', 12, 2)->default(0)->after('total');
            $table->decimal('total_pagar', 12, 2)->after('monto_seña'); // total - monto_seña
            $table->string('ruc_factura')->nullable(); // Por si el RUC es diferente al del cliente
            $table->string('razon_social_factura')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
