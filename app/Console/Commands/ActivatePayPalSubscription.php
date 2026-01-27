<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayPalService;
use App\Models\User;
use App\Models\Plan;
use App\Models\PaymentLog;
use App\Models\UserSubscription;

class ActivatePayPalSubscription extends Command
{
    protected $signature = 'paypal:activate-subscription {subscription_id} {--email=} {--plan_id=} {--tipo=mensual}';
    protected $description = 'Activar manualmente una suscripción de PayPal en la base de datos';

    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        parent::__construct();
        $this->paypalService = $paypalService;
    }

    public function handle()
    {
        $subscriptionId = $this->argument('subscription_id');
        $email = $this->option('email');
        $planId = $this->option('plan_id');
        $tipo = $this->option('tipo');

        $this->info("Activando suscripción: {$subscriptionId}");
        $this->newLine();

        // Verificar si ya existe en la base de datos
        $existingSubscription = UserSubscription::where('paypal_subscription_id', $subscriptionId)->first();
        if ($existingSubscription) {
            $this->warn('Esta suscripción ya existe en la base de datos:');
            $this->table(
                ['ID', 'Usuario', 'Plan', 'Estado', 'Fecha'],
                [[
                    $existingSubscription->id,
                    $existingSubscription->user->email ?? 'N/A',
                    $existingSubscription->plan->nombre ?? 'N/A',
                    $existingSubscription->estado,
                    $existingSubscription->created_at->format('Y-m-d H:i:s'),
                ]]
            );
            return 0;
        }

        // Obtener detalles de PayPal
        $this->line('→ Verificando suscripción en PayPal...');
        $paypalSubscription = $this->paypalService->getSubscription($subscriptionId);

        if (!$paypalSubscription) {
            $this->error('No se pudo obtener la suscripción de PayPal');
            return 1;
        }

        if ($paypalSubscription['status'] !== 'ACTIVE' && $paypalSubscription['status'] !== 'APPROVED') {
            $this->error('La suscripción no está activa en PayPal. Estado: ' . $paypalSubscription['status']);
            return 1;
        }

        $this->info('✓ Suscripción verificada en PayPal - Estado: ' . $paypalSubscription['status']);

        // Obtener email del suscriptor si no se proporcionó
        if (!$email && isset($paypalSubscription['subscriber']['email_address'])) {
            $email = $paypalSubscription['subscriber']['email_address'];
        }

        if (!$email) {
            $email = $this->ask('Email del usuario:');
        }

        // Buscar usuario
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("No se encontró usuario con email: {$email}");
            return 1;
        }

        $this->info("✓ Usuario encontrado: {$user->name} ({$user->email})");

        // Buscar plan
        if (!$planId) {
            // Intentar encontrar el plan por el plan_id de PayPal
            $paypalPlanId = $paypalSubscription['plan_id'] ?? null;
            if ($paypalPlanId) {
                $plan = Plan::where('paypal_plan_id_mensual', $paypalPlanId)
                    ->orWhere('paypal_plan_id_anual', $paypalPlanId)
                    ->first();

                if ($plan) {
                    $planId = $plan->id;
                    // Determinar el tipo basado en qué plan_id coincide
                    if ($plan->paypal_plan_id_anual === $paypalPlanId) {
                        $tipo = 'anual';
                    }
                }
            }
        }

        if (!$planId) {
            // Mostrar planes disponibles
            $plans = Plan::where('activo', true)->get();
            $this->table(
                ['ID', 'Nombre', 'Mensual', 'Anual'],
                $plans->map(fn($p) => [$p->id, $p->nombre, $p->precio_mensual . '€', $p->precio_anual . '€'])
            );
            $planId = $this->ask('ID del plan:');
        }

        $plan = Plan::find($planId);
        if (!$plan) {
            $this->error("No se encontró plan con ID: {$planId}");
            return 1;
        }

        $this->info("✓ Plan: {$plan->nombre} ({$tipo})");

        // Confirmar
        if (!$this->confirm('¿Crear la suscripción con estos datos?')) {
            $this->info('Operación cancelada');
            return 0;
        }

        // Determinar monto
        $monto = $tipo === 'anual' ? $plan->precio_anual : $plan->precio_mensual;

        // Crear suscripción
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'tipo' => $tipo,
            'estado' => 'activa',
            'metodo_pago' => 'paypal',
            'paypal_subscription_id' => $subscriptionId,
            'monto_pagado' => $monto,
        ]);

        $subscription->activate();

        // Registrar el pago en payment_logs
        $payerEmail = $paypalSubscription['subscriber']['email_address'] ?? null;
        $payerName = isset($paypalSubscription['subscriber']['name'])
            ? trim(($paypalSubscription['subscriber']['name']['given_name'] ?? '') . ' ' . ($paypalSubscription['subscriber']['name']['surname'] ?? ''))
            : null;

        PaymentLog::logSuccess([
            'user_id' => $user->id,
            'user_subscription_id' => $subscription->id,
            'plan_id' => $plan->id,
            'paypal_subscription_id' => $subscriptionId,
            'paypal_plan_id' => $paypalSubscription['plan_id'] ?? null,
            'amount' => $monto,
            'currency' => 'EUR',
            'payment_method' => 'paypal',
            'type' => 'subscription',
            'payer_email' => $payerEmail,
            'payer_name' => $payerName,
            'description' => "Suscripción {$tipo} al plan {$plan->nombre} (activación manual)",
            'paypal_response' => $paypalSubscription,
        ]);

        $this->newLine();
        $this->info('✅ Suscripción creada exitosamente!');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID Local', $subscription->id],
                ['ID PayPal', $subscriptionId],
                ['Usuario', $user->email],
                ['Plan', $plan->nombre],
                ['Tipo', $tipo],
                ['Monto', $monto . '€'],
                ['Estado', 'activa'],
                ['Fecha inicio', $subscription->fecha_inicio->format('Y-m-d H:i:s')],
                ['Fecha expiración', $subscription->fecha_expiracion->format('Y-m-d H:i:s')],
            ]
        );

        return 0;
    }
}
