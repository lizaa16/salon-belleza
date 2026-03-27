<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cajas', function (Blueprint $column) {
            // Agregamos el campo como nullable para que no de error con los registros viejos
            $column->text('observaciones')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('cajas', function (Blueprint $column) {
            $column->dropColumn('observaciones');
        });
    }
};