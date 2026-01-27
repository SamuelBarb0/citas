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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();

            // Identificadores de PayPal
            $table->string('paypal_subscription_id')->nullable()->index();
            $table->string('paypal_transaction_id')->nullable()->index();
            $table->string('paypal_plan_id')->nullable();

            // Información del pago
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method')->default('paypal'); // paypal, stripe, etc.

            // Estado y tipo
            $table->string('status'); // pending, completed, failed, refunded
            $table->string('type')->default('subscription'); // subscription, one_time, renewal

            // Información adicional
            $table->string('payer_email')->nullable();
            $table->string('payer_name')->nullable();
            $table->text('description')->nullable();

            // Datos raw de PayPal para referencia
            $table->json('paypal_response')->nullable();

            // Errores si los hay
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
