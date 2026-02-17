<?php
/**
 * Script para corregir la configuración de planes en la base de datos.
 *
 * Problema: El plan "Mensual" tiene precio_anual = 29.99 y un PayPal Plan ID anual
 * que no debería tener. Esto causa que el sync-plans cree billing plans duplicados.
 *
 * Solución:
 * - Plan "Mensual": limpiar precio_anual y paypal_plan_id_anual
 * - Plan "Anual": ya está correcto (solo tiene precio_anual)
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Corrección de planes ===\n\n";

// Plan Mensual: quitar precio_anual y paypal_plan_id_anual
$planMensual = \App\Models\Plan::where('slug', 'mensual')->first();

if ($planMensual) {
    echo "Plan Mensual encontrado (ID: {$planMensual->id})\n";
    echo "  ANTES:\n";
    echo "    precio_mensual: {$planMensual->precio_mensual}\n";
    echo "    precio_anual: {$planMensual->precio_anual}\n";
    echo "    paypal_plan_id_mensual: " . ($planMensual->paypal_plan_id_mensual ?? 'NULL') . "\n";
    echo "    paypal_plan_id_anual: " . ($planMensual->paypal_plan_id_anual ?? 'NULL') . "\n";

    $planMensual->update([
        'precio_anual' => 0.00,
        'paypal_plan_id_anual' => null,
    ]);

    $planMensual->refresh();
    echo "  DESPUÉS:\n";
    echo "    precio_mensual: {$planMensual->precio_mensual}\n";
    echo "    precio_anual: {$planMensual->precio_anual}\n";
    echo "    paypal_plan_id_mensual: " . ($planMensual->paypal_plan_id_mensual ?? 'NULL') . "\n";
    echo "    paypal_plan_id_anual: " . ($planMensual->paypal_plan_id_anual ?? 'NULL') . "\n";
    echo "  ✓ Plan Mensual corregido\n\n";
} else {
    echo "  ✗ Plan Mensual no encontrado\n\n";
}

// Verificar Plan Anual
$planAnual = \App\Models\Plan::where('slug', 'anual')->first();

if ($planAnual) {
    echo "Plan Anual (ID: {$planAnual->id})\n";
    echo "  precio_mensual: {$planAnual->precio_mensual}\n";
    echo "  precio_anual: {$planAnual->precio_anual}\n";
    echo "  paypal_plan_id_mensual: " . ($planAnual->paypal_plan_id_mensual ?? 'NULL') . "\n";
    echo "  paypal_plan_id_anual: " . ($planAnual->paypal_plan_id_anual ?? 'NULL') . "\n";
    echo "  ✓ Plan Anual OK (no necesita cambios)\n\n";
} else {
    echo "  ✗ Plan Anual no encontrado\n\n";
}

// Resumen final
echo "=== Resumen final de todos los planes ===\n\n";
$allPlans = \App\Models\Plan::all();
foreach ($allPlans as $p) {
    echo "{$p->nombre} (slug: {$p->slug})\n";
    echo "  precio_mensual: {$p->precio_mensual} | precio_anual: {$p->precio_anual}\n";
    echo "  PP mensual: " . ($p->paypal_plan_id_mensual ?? 'NULL') . "\n";
    echo "  PP anual: " . ($p->paypal_plan_id_anual ?? 'NULL') . "\n";
    echo "---\n";
}

echo "\n✅ Corrección completada.\n";
echo "NOTA: El PayPal billing plan P-7NS02720TD866302DNGJ263I (anual del plan Mensual)\n";
echo "sigue existiendo en PayPal pero ya no se usa. Puedes desactivarlo desde el dashboard de PayPal.\n";
