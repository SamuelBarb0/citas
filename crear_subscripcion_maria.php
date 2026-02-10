<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Plan;
use App\Models\UserSubscription;
use Carbon\Carbon;

echo "=== CREAR SUSCRIPCIÓN PLAN DE PAGO ===" . PHP_EOL . PHP_EOL;

// Buscar usuario
echo "Buscando usuario maria@citasmallorca.es..." . PHP_EOL;
$user = User::where('email', 'maria@citasmallorca.es')->first();

if (!$user) {
    echo "❌ Usuario NO encontrado" . PHP_EOL;
    exit(1);
}

echo "✅ Usuario encontrado: {$user->name} (ID: {$user->id})" . PHP_EOL . PHP_EOL;

// Buscar plan de pago (que NO sea gratuito)
echo "Buscando planes de pago disponibles..." . PHP_EOL;
$planesPago = Plan::where(function($query) {
    $query->where('precio_mensual', '>', 0)
          ->orWhere('precio_anual', '>', 0);
})->where('activo', true)->get();

if ($planesPago->isEmpty()) {
    echo "❌ No hay planes de pago activos" . PHP_EOL;
    exit(1);
}

echo "Planes de pago encontrados:" . PHP_EOL;
foreach ($planesPago as $index => $plan) {
    echo "  [{$index}] ID: {$plan->id} | {$plan->nombre} | Mensual: {$plan->precio_mensual}€ | Anual: {$plan->precio_anual}€" . PHP_EOL;
}
echo PHP_EOL;

// Seleccionar el primer plan de pago (el que no sea gratuito)
$planSeleccionado = $planesPago->first();

echo "✅ Plan seleccionado: {$planSeleccionado->nombre} (ID: {$planSeleccionado->id})" . PHP_EOL;
echo "   Precio mensual: {$planSeleccionado->precio_mensual}€" . PHP_EOL;
echo "   Precio anual: {$planSeleccionado->precio_anual}€" . PHP_EOL . PHP_EOL;

// Verificar si ya tiene suscripción activa
$subscripcionActiva = UserSubscription::where('user_id', $user->id)
    ->where('estado', 'activa')
    ->where('fecha_expiracion', '>', now())
    ->first();

if ($subscripcionActiva) {
    echo "⚠️  El usuario ya tiene una suscripción activa:" . PHP_EOL;
    echo "   Plan ID: {$subscripcionActiva->plan_id}" . PHP_EOL;
    echo "   Expira: {$subscripcionActiva->fecha_expiracion}" . PHP_EOL;
    echo "   Cancelando la actual..." . PHP_EOL . PHP_EOL;

    // Cancelar la anterior
    $subscripcionActiva->update(['estado' => 'cancelada']);
    echo "✅ Suscripción anterior cancelada" . PHP_EOL . PHP_EOL;
}

// Crear nueva suscripción
echo "Creando suscripción {$planSeleccionado->nombre}..." . PHP_EOL;

$fechaInicio = Carbon::now();
$fechaExpiracion = Carbon::now()->addMonth(); // 1 mes

$subscripcion = UserSubscription::create([
    'user_id' => $user->id,
    'plan_id' => $planSeleccionado->id,
    'tipo' => 'mensual',
    'estado' => 'activa',
    'fecha_inicio' => $fechaInicio,
    'fecha_expiracion' => $fechaExpiracion,
    'auto_renovacion' => false,
    'metodo_pago' => 'manual',
    'transaction_id' => 'ADMIN_MANUAL_' . time(),
    'monto_pagado' => 0,
    'likes_usados_hoy' => 0,
    'ultimo_reset_likes' => $fechaInicio,
    'boosts_restantes' => $planSeleccionado->boost_mensual ? 1 : 0,
    'mensajes_enviados_esta_semana' => 0,
    'ultimo_reset_mensajes' => $fechaInicio,
]);

echo "✅ ¡Suscripción creada exitosamente!" . PHP_EOL . PHP_EOL;

echo "=== DETALLES DE LA SUSCRIPCIÓN ===" . PHP_EOL;
echo "Usuario: {$user->name} ({$user->email})" . PHP_EOL;
echo "Plan: {$planSeleccionado->nombre}" . PHP_EOL;
echo "Tipo: Mensual" . PHP_EOL;
echo "Estado: Activa" . PHP_EOL;
echo "Fecha inicio: {$fechaInicio->format('d/m/Y H:i')}" . PHP_EOL;
echo "Fecha expiración: {$fechaExpiracion->format('d/m/Y H:i')}" . PHP_EOL;
echo "ID Suscripción: {$subscripcion->id}" . PHP_EOL;
echo "Boosts disponibles: {$subscripcion->boosts_restantes}" . PHP_EOL . PHP_EOL;

echo "🎉 ¡Listo! María ahora tiene plan {$planSeleccionado->nombre}." . PHP_EOL;
