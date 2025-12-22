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
        // Agregar campos a la tabla plans
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('puede_iniciar_conversacion')->default(false)->after('matches_ilimitados');
            $table->integer('mensajes_semanales_gratis')->default(0)->after('puede_iniciar_conversacion'); // Para plan Básico: 3 mensajes/semana a usuarios Gratis
            $table->boolean('mensajes_ilimitados')->default(false)->after('mensajes_semanales_gratis');
        });

        // Agregar campos a la tabla user_subscriptions
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->integer('mensajes_enviados_esta_semana')->default(0)->after('boosts_restantes');
            $table->date('ultimo_reset_mensajes')->nullable()->after('mensajes_enviados_esta_semana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['puede_iniciar_conversacion', 'mensajes_semanales_gratis', 'mensajes_ilimitados']);
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['mensajes_enviados_esta_semana', 'ultimo_reset_mensajes']);
        });
    }
};
