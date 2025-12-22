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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');

            // Información de la suscripción
            $table->enum('tipo', ['mensual', 'anual'])->default('mensual');
            $table->enum('estado', ['activa', 'cancelada', 'expirada', 'pendiente'])->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_expiracion')->nullable();
            $table->boolean('auto_renovacion')->default(true);

            // Información de pago
            $table->enum('metodo_pago', ['stripe', 'paypal'])->nullable();
            $table->string('stripe_subscription_id')->nullable(); // ID de suscripción en Stripe
            $table->string('paypal_subscription_id')->nullable(); // ID de suscripción en PayPal
            $table->string('transaction_id')->nullable(); // ID de transacción
            $table->decimal('monto_pagado', 8, 2)->nullable();

            // Límites y uso
            $table->integer('likes_usados_hoy')->default(0);
            $table->date('ultimo_reset_likes')->nullable();
            $table->integer('super_likes_restantes')->default(0);
            $table->integer('boosts_restantes')->default(0);
            $table->date('ultimo_boost')->nullable();

            $table->timestamps();

            // Índices para mejorar el rendimiento
            $table->index(['user_id', 'estado']);
            $table->index('fecha_expiracion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
