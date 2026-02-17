<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$plans = \App\Models\Plan::all();
foreach ($plans as $p) {
    echo "ID: {$p->id}\n";
    echo "  Nombre: {$p->nombre}\n";
    echo "  Slug: {$p->slug}\n";
    echo "  Precio Mensual: {$p->precio_mensual}\n";
    echo "  Precio Anual: {$p->precio_anual}\n";
    echo "  PayPal Plan ID Mensual: " . ($p->paypal_plan_id_mensual ?? 'NULL') . "\n";
    echo "  PayPal Plan ID Anual: " . ($p->paypal_plan_id_anual ?? 'NULL') . "\n";
    echo "  Activo: " . ($p->activo ? 'SI' : 'NO') . "\n";
    echo "---\n";
}
