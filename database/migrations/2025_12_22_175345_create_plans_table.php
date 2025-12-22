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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Gratis", "Premium", "Gold"
            $table->string('slug')->unique(); // Ej: "free", "premium", "gold"
            $table->text('descripcion')->nullable();
            $table->decimal('precio_mensual', 8, 2)->default(0); // Precio mensual
            $table->decimal('precio_anual', 8, 2)->default(0); // Precio anual (con descuento)
            $table->string('stripe_price_id_monthly')->nullable(); // ID de precio en Stripe (mensual)
            $table->string('stripe_price_id_yearly')->nullable(); // ID de precio en Stripe (anual)
            $table->string('paypal_plan_id_monthly')->nullable(); // ID de plan en PayPal (mensual)
            $table->string('paypal_plan_id_yearly')->nullable(); // ID de plan en PayPal (anual)

            // Características del plan
            $table->integer('likes_diarios')->default(10); // Límite de likes por día
            $table->integer('super_likes_mes')->default(0); // Super likes por mes
            $table->boolean('ver_quien_te_gusta')->default(false); // Ver quién te ha dado like
            $table->boolean('matches_ilimitados')->default(false);
            $table->boolean('rewind')->default(false); // Deshacer último swipe
            $table->boolean('boost_mensual')->default(false); // Destacar perfil 1 vez/mes
            $table->boolean('sin_anuncios')->default(false);
            $table->boolean('modo_incognito')->default(false); // Solo visible para quien le des like
            $table->boolean('verificacion_prioritaria')->default(false);
            $table->integer('fotos_adicionales')->default(6); // Máximo de fotos (6 gratis, 10+ premium)

            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0); // Para ordenar los planes en la vista
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
