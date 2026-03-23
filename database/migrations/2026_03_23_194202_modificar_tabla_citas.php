<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->decimal('seña_monto', 10, 2)->nullable()->after('notas');
            $table->enum('seña_metodo_pago', ['efectivo', 'tarjeta', 'transferencia'])->nullable()->after('seña_monto');
            $table->unsignedBigInteger('reagendado_de')->nullable()->after('seña_metodo_pago');
            $table->foreign('reagendado_de')->references('id')->on('citas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['reagendado_de']);
            $table->dropColumn(['seña_monto', 'seña_metodo_pago', 'reagendado_de']);
        });
    }
};
