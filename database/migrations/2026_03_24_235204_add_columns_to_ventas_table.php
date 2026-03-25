<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('total_bruto', 15, 0);       // Ej: 200.000
            //$table->decimal('monto_seña', 15, 0)->default(0); // Ej: 50.000 (Viene de la cita)
            $table->decimal('monto_final_cobrado', 15, 0);        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Es vital definir cómo deshacer el cambio
            $table->dropColumn(['total_bruto', 'monto_seña', 'monto_final_cobrado']);
        });
    }
};
