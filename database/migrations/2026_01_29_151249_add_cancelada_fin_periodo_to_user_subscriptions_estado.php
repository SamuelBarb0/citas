<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el enum para incluir 'cancelada_fin_periodo'
        DB::statement("ALTER TABLE user_subscriptions MODIFY COLUMN estado ENUM('activa', 'cancelada', 'expirada', 'pendiente', 'cancelada_fin_periodo') DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores originales
        DB::statement("ALTER TABLE user_subscriptions MODIFY COLUMN estado ENUM('activa', 'cancelada', 'expirada', 'pendiente') DEFAULT 'pendiente'");
    }
};
