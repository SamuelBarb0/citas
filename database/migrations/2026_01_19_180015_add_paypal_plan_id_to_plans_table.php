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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('paypal_plan_id_mensual')->nullable()->after('precio_anual');
            $table->string('paypal_plan_id_anual')->nullable()->after('paypal_plan_id_mensual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['paypal_plan_id_mensual', 'paypal_plan_id_anual']);
        });
    }
};
