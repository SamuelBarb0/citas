<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\Plan;
use App\Models\User;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayPalWebhookController extends Controller
{
    /**
     * Número máximo de reintentos para buscar la suscripción (Race Condition fix)
     */
    private const MAX_RETRIES = 5;

    /**
     * Tiempo de espera entre reintentos en segundos
     */
    private const RETRY_DELAY_SECONDS = 2;

    /**
     * Manejar webhooks de PayPal para suscripciones
     */
    public function handle(Request $request)
    {
        // Log del webhook recibido
        Log::info('=== PAYPAL WEBHOOK RECIBIDO ===', [
            'event_type' => $request->input('event_type'),
            'resource_id' => $request->input('resource.id'),
            'timestamp' => now()->toDateTimeString()
        ]);

        $eventType = $request->input('event_type');
        $resource = $request->input('resource');

        // Obtener el ID de suscripción de PayPal
        $subscriptionId = $resource['id'] ?? null;

        if (!$subscriptionId) {
            Log::error('PayPal Webhook: No subscription ID found in resource');
            // Devolvemos 200 OK para que PayPal no reintente (evitar loops)
            return response()->json(['status' => 'acknowledged', 'message' => 'No subscription ID'], 200);
        }

        // ============================================================
        // FIX RACE CONDITION: Buscar con reintentos
        // El webhook puede llegar antes de que el frontend guarde la suscripción
        // ============================================================
        $subscription = $this->findSubscriptionWithRetry($subscriptionId);

        if (!$subscription) {
            Log::warning('PayPal Webhook: Subscription not found after retries', [
                'subscription_id' => $subscriptionId,
                'event_type' => $eventType,
                'max_retries' => self::MAX_RETRIES
            ]);

            // ============================================================
            // FALLBACK: Intentar crear la suscripción desde el webhook
            // Solo para eventos de activación donde tenemos suficiente info
            // ============================================================
            if ($eventType === 'BILLING.SUBSCRIPTION.ACTIVATED') {
                $subscription = $this->createSubscriptionFromWebhook($subscriptionId, $resource);

                if ($subscription) {
                    Log::info('PayPal Webhook: Suscripción creada desde webhook (fallback)', [
                        'subscription_id' => $subscriptionId,
                        'local_subscription_id' => $subscription->id
                    ]);
                } else {
                    // Si no pudimos crear, devolvemos 200 OK para no bloquear PayPal
                    // pero logueamos para revisión manual
                    Log::error('PayPal Webhook: No se pudo crear suscripción desde webhook', [
                        'subscription_id' => $subscriptionId,
                        'resource' => $resource
                    ]);
                    return response()->json([
                        'status' => 'acknowledged',
                        'message' => 'Subscription not found, logged for manual review'
                    ], 200);
                }
            } else {
                // Para otros eventos, simplemente acknowledgeamos sin error
                // PayPal podría enviar eventos de suscripciones que no existen en nuestro sistema
                Log::info('PayPal Webhook: Ignoring event for unknown subscription', [
                    'subscription_id' => $subscriptionId,
                    'event_type' => $eventType
                ]);
                return response()->json(['status' => 'acknowledged'], 200);
            }
        }

        // Manejar diferentes tipos de eventos
        switch ($eventType) {
            case 'BILLING.SUBSCRIPTION.ACTIVATED':
                $this->handleSubscriptionActivated($subscription, $resource);
                break;

            case 'PAYMENT.SALE.COMPLETED':
                $this->handlePaymentCompleted($subscription, $resource);
                break;

            case 'BILLING.SUBSCRIPTION.PAYMENT.FAILED':
                $this->handlePaymentFailed($subscription, $resource);
                break;

            case 'BILLING.SUBSCRIPTION.CANCELLED':
                $this->handleSubscriptionCancelled($subscription, $resource);
                break;

            case 'BILLING.SUBSCRIPTION.SUSPENDED':
                $this->handleSubscriptionSuspended($subscription, $resource);
                break;

            case 'BILLING.SUBSCRIPTION.EXPIRED':
                $this->handleSubscriptionExpired($subscription, $resource);
                break;

            default:
                Log::info('PayPal Webhook: Unhandled event type', ['event_type' => $eventType]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Buscar suscripción con reintentos para manejar Race Condition
     * El webhook puede llegar antes de que el frontend complete el guardado
     */
    private function findSubscriptionWithRetry(string $subscriptionId): ?UserSubscription
    {
        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            $subscription = UserSubscription::where('paypal_subscription_id', $subscriptionId)->first();

            if ($subscription) {
                Log::info('PayPal Webhook: Suscripción encontrada', [
                    'subscription_id' => $subscriptionId,
                    'attempt' => $attempt,
                    'local_id' => $subscription->id
                ]);
                return $subscription;
            }

            // Si no es el último intento, esperar antes de reintentar
            if ($attempt < self::MAX_RETRIES) {
                Log::info('PayPal Webhook: Suscripción no encontrada, reintentando...', [
                    'subscription_id' => $subscriptionId,
                    'attempt' => $attempt,
                    'next_retry_in' => self::RETRY_DELAY_SECONDS . 's'
                ]);
                sleep(self::RETRY_DELAY_SECONDS);
            }
        }

        return null;
    }

    /**
     * Crear suscripción desde el webhook cuando no existe en BD (fallback)
     * Esto maneja el caso donde el usuario cerró el navegador antes de completar
     */
    private function createSubscriptionFromWebhook(string $subscriptionId, array $resource): ?UserSubscription
    {
        try {
            // Obtener información del suscriptor desde el webhook
            $subscriberEmail = $resource['subscriber']['email_address'] ?? null;
            $planId = $resource['plan_id'] ?? null;

            if (!$subscriberEmail || !$planId) {
                Log::warning('PayPal Webhook Fallback: Datos insuficientes', [
                    'subscription_id' => $subscriptionId,
                    'has_email' => !empty($subscriberEmail),
                    'has_plan_id' => !empty($planId)
                ]);
                return null;
            }

            // Buscar usuario por email
            $user = User::where('email', $subscriberEmail)->first();

            if (!$user) {
                Log::warning('PayPal Webhook Fallback: Usuario no encontrado', [
                    'email' => $subscriberEmail,
                    'subscription_id' => $subscriptionId
                ]);
                return null;
            }

            // Buscar el plan local por el plan_id de PayPal
            $plan = Plan::where('paypal_plan_id_mensual', $planId)
                ->orWhere('paypal_plan_id_anual', $planId)
                ->first();

            if (!$plan) {
                Log::warning('PayPal Webhook Fallback: Plan no encontrado', [
                    'paypal_plan_id' => $planId,
                    'subscription_id' => $subscriptionId
                ]);
                return null;
            }

            // Determinar si es mensual o anual
            $tipo = ($plan->paypal_plan_id_mensual === $planId) ? 'mensual' : 'anual';
            $montoPagado = ($tipo === 'mensual') ? $plan->precio_mensual : $plan->precio_anual;

            // Verificar que no exista ya una suscripción activa para este usuario
            $existingActive = UserSubscription::where('user_id', $user->id)
                ->where('estado', 'activa')
                ->where('fecha_expiracion', '>', now())
                ->first();

            if ($existingActive) {
                Log::info('PayPal Webhook Fallback: Usuario ya tiene suscripción activa', [
                    'user_id' => $user->id,
                    'existing_subscription_id' => $existingActive->id
                ]);
                // Actualizar con el nuevo paypal_subscription_id si es diferente
                if ($existingActive->paypal_subscription_id !== $subscriptionId) {
                    $existingActive->update(['paypal_subscription_id' => $subscriptionId]);
                }
                return $existingActive;
            }

            // Crear la suscripción con todos los campos necesarios para activación inmediata
            $duracionMeses = $tipo === 'anual' ? 12 : 1;

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tipo' => $tipo,
                'estado' => 'activa',
                'metodo_pago' => 'paypal',
                'paypal_subscription_id' => $subscriptionId,
                'monto_pagado' => $montoPagado,
                'fecha_inicio' => now(),
                'fecha_expiracion' => now()->addMonths($duracionMeses),
                'auto_renovacion' => true,
                'likes_usados_hoy' => 0,
                'ultimo_reset_likes' => now(),
                'boosts_restantes' => $plan->boost_mensual ? 1 : 0,
                'mensajes_enviados_esta_semana' => 0,
                'ultimo_reset_mensajes' => now(),
            ]);

            // Registrar el pago
            PaymentLog::logSuccess([
                'user_id' => $user->id,
                'user_subscription_id' => $subscription->id,
                'plan_id' => $plan->id,
                'paypal_subscription_id' => $subscriptionId,
                'paypal_plan_id' => $planId,
                'amount' => $montoPagado,
                'currency' => 'EUR',
                'payment_method' => 'paypal',
                'type' => 'subscription',
                'payer_email' => $subscriberEmail,
                'description' => "Suscripción {$tipo} al plan {$plan->nombre} (creada desde webhook)",
                'paypal_response' => $resource,
            ]);

            Log::info('PayPal Webhook Fallback: Suscripción creada exitosamente', [
                'subscription_id' => $subscriptionId,
                'local_subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'plan' => $plan->nombre,
                'tipo' => $tipo
            ]);

            // Enviar email de confirmación
            Log::info('PayPal Webhook Fallback: Iniciando envío de email', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'subscription_id' => $subscription->id
            ]);

            try {
                $mailHost = config('mail.mailers.smtp.host');
                $mailUsername = config('mail.mailers.smtp.username');
                $mailFromAddress = config('mail.from.address');

                Log::info('PayPal Webhook Fallback: Configuración de correo', [
                    'mail_host' => $mailHost,
                    'mail_username' => $mailUsername,
                    'mail_from' => $mailFromAddress
                ]);

                $mailConfigured = $mailUsername !== null && $mailUsername !== 'tu-email@gmail.com';

                if ($mailConfigured) {
                    Log::info('PayPal Webhook Fallback: Intentando enviar email...');

                    $user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));

                    Log::info('PayPal Webhook Fallback: ✅ Email enviado exitosamente', [
                        'user_email' => $user->email,
                        'to' => $user->email
                    ]);
                } else {
                    Log::warning('PayPal Webhook Fallback: ⚠️ Email NO enviado - configuración no válida', [
                        'mail_username' => $mailUsername
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('PayPal Webhook Fallback: ❌ Error enviando email', [
                    'error' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return $subscription;

        } catch (\Exception $e) {
            Log::error('PayPal Webhook Fallback: Excepción', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Manejar suscripción activada
     */
    private function handleSubscriptionActivated($subscription, $resource)
    {
        Log::info('PayPal: Subscription activated', ['subscription_id' => $subscription->id]);

        $subscription->activate();
    }

    /**
     * Manejar pago completado (renovación exitosa)
     */
    private function handlePaymentCompleted($subscription, $resource)
    {
        Log::info('PayPal: Payment completed', [
            'subscription_id' => $subscription->id,
            'amount' => $resource['amount']['total'] ?? null
        ]);

        $transactionId = $resource['id'] ?? null;
        $montoPagado = $resource['amount']['total'] ?? null;

        // Renovar la suscripción
        $subscription->renew($transactionId, $montoPagado);

        // Enviar email de confirmación de renovación
        try {
            if (config('mail.username') !== 'tu-email@gmail.com') {
                $subscription->user->notify(new \App\Notifications\SubscriptionRenewedNotification($subscription));
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send renewal notification email', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);
        }
    }

    /**
     * Manejar fallo de pago
     */
    private function handlePaymentFailed($subscription, $resource)
    {
        Log::warning('PayPal: Payment failed', [
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id
        ]);

        // Marcar como impagada y bloquear acceso
        $subscription->markAsUnpaid();

        // Enviar email notificando el fallo de pago
        try {
            if (config('mail.username') !== 'tu-email@gmail.com') {
                $subscription->user->notify(new \App\Notifications\PaymentFailedNotification($subscription));
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send payment failed notification email', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);
        }
    }

    /**
     * Manejar suscripción cancelada
     */
    private function handleSubscriptionCancelled($subscription, $resource)
    {
        Log::info('PayPal: Subscription cancelled', ['subscription_id' => $subscription->id]);

        $subscription->cancel();

        // TODO: Enviar email de confirmación de cancelación
    }

    /**
     * Manejar suscripción suspendida
     */
    private function handleSubscriptionSuspended($subscription, $resource)
    {
        Log::warning('PayPal: Subscription suspended', ['subscription_id' => $subscription->id]);

        $subscription->markAsUnpaid();

        // TODO: Enviar email notificando la suspensión
    }

    /**
     * Manejar suscripción expirada
     */
    private function handleSubscriptionExpired($subscription, $resource)
    {
        Log::info('PayPal: Subscription expired', ['subscription_id' => $subscription->id]);

        $subscription->update(['estado' => 'expirada']);

        // TODO: Enviar email notificando la expiración
    }
}
