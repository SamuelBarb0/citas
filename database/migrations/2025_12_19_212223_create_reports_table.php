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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade'); // Usuario que reporta
            $table->foreignId('reported_user_id')->constrained('users')->onDelete('cascade'); // Usuario reportado
            $table->enum('reason', ['inapropiado', 'spam', 'acoso', 'suplantacion', 'menor_edad', 'otro']); // Razón del reporte
            $table->text('description')->nullable(); // Descripción adicional
            $table->enum('status', ['pendiente', 'revisado', 'accion_tomada', 'descartado'])->default('pendiente'); // Estado del reporte
            $table->timestamps();

            // Índice para búsquedas rápidas
            $table->index(['reported_user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
