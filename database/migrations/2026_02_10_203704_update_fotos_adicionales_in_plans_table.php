<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Actualiza el límite de fotos adicionales a 10 para todos los planes
     */
    public function up(): void
    {
        // Actualizar todos los planes para tener 10 fotos adicionales
        DB::table('plans')->update(['fotos_adicionales' => 10]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al valor por defecto de la migración original (6)
        DB::table('plans')->update(['fotos_adicionales' => 6]);
    }
};
