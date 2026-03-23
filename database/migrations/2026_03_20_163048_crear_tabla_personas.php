<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono')->nullable();
            $table->string('documento')->nullable();
            $table->string('email')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
            $table->boolean('activo')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
