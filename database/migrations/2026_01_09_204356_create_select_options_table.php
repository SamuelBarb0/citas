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
        Schema::create('select_options', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // 'ciudad', 'genero', 'orientacion_sexual', etc.
            $table->string('valor'); // El valor que se guardará en la BD
            $table->string('etiqueta'); // El texto que se mostrará al usuario
            $table->string('grupo')->nullable(); // Para agrupar opciones (ej: "Principales Ciudades", "Todos los Municipios")
            $table->integer('orden')->default(0); // Para ordenar las opciones
            $table->boolean('activo')->default(true); // Para activar/desactivar opciones
            $table->timestamps();

            // Índices para mejorar el rendimiento
            $table->index('tipo');
            $table->index(['tipo', 'activo', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('select_options');
    }
};
