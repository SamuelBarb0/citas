<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Plan;
use App\Models\UserSubscription;
use App\Notifications\SubscriptionActivatedNotification;

echo "=== PROBANDO EMAIL DE SUSCRIPCIÓN ACTIVADA ===\n\n";

try {
    // Buscar un usuario de prueba (el primero disponible)
    $user = User::first();

    if (!$user) {
        echo "❌ No hay usuarios en la base de datos\n";
        echo "💡 Crea un usuario primero o regístrate en la app\n";
        exit(1);
    }

    echo "👤 Usuario encontrado: {$user->name} ({$user->email})\n\n";

    // Buscar un plan (idealmente Premium o Básico)
    $plan = Plan::where('slug', 'premium')->first() ?? Plan::where('slug', 'basico')->first() ?? Plan::first();

    if (!$plan) {
        echo "❌ No hay planes en la base de datos\n";
        echo "💡 Ejecuta: php artisan db:seed --class=PlanSeeder\n";
        exit(1);
    }

    echo "📋 Plan encontrado: {$plan->nombre}\n\n";

    // Crear una suscripción de prueba temporal (no se guarda en BD)
    $subscription = new UserSubscription([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'tipo' => 'mensual',
        'estado' => 'activa',
        'metodo_pago' => 'paypal',
        'monto_pagado' => $plan->precio_mensual,
        'fecha_inicio' => now(),
        'fecha_expiracion' => now()->addMonth(),
    ]);

    // Asignar el plan al objeto subscription para que la plantilla pueda accederlo
    $subscription->setRelation('plan', $plan);

    echo "💌 Enviando email de prueba...\n\n";

    // Enviar notificación
    $user->notify(new SubscriptionActivatedNotification($subscription));

    echo "✅ Email enviado exitosamente a: {$user->email}\n";
    echo "📧 Revisa tu bandeja de entrada de info@citasmallorca.es\n\n";
    echo "📄 Asunto: ¡Tu suscripción a Citas Mallorca está activa!\n";
    echo "🎨 Plantilla: resources/views/emails/subscription-activated.blade.php\n";

} catch (\Exception $e) {
    echo "❌ Error al enviar email:\n";
    echo $e->getMessage() . "\n\n";
    echo "📍 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n\n";
    echo "Traza:\n" . $e->getTraceAsString() . "\n";
}
