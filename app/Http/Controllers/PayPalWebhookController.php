<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    /**
     * Manejar webhooks de PayPal para suscripciones
     */
    public function handle(Request $request)
    {
        // Log del webhook recibido
        Log::info('PayPal Webhook received', [
            'event_type' => $request->input('event_type'),
            'resource' => $request->input('resource')
        ]);

        $eventType = $request->input('event_type');
        $resource = $request->input('resource');

        // Obtener el ID de suscripción de PayPal
        $subscriptionId = $resource['id'] ?? null;

        if (!$subscriptionId) {
            Log::error('PayPal Webhook: No subscription ID found');
            return response()->json(['error' => 'No subscription ID'], 400);
        }

        // Buscar la suscripción en nuestra base de datos
        $subscription = UserSubscription::where('paypal_subscription_id', $subscriptionId)->first();

        if (!$subscription) {
            Log::warning('PayPal Webhook: Subscription not found', ['subscription_id' => $subscriptionId]);
            return response()->json(['error' => 'Subscription not found'], 404);
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

        // Enviar email de confirmación de pago
        try {
            if (config('mail.username') !== 'tu-email@gmail.com') {
                $subscription->user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send payment notification email', [
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
