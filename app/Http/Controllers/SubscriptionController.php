<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Mostrar todos los planes disponibles
     */
    public function index()
    {
        $plans = Plan::active()->ordered()->get();
        $currentSubscription = Auth::check() ? Auth::user()->activeSubscription : null;

        return view('subscriptions.index', compact('plans', 'currentSubscription'));
    }

    /**
     * Mostrar el dashboard de suscripción del usuario
     */
    public function dashboard()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;
        $history = $user->subscriptions()->latest()->take(10)->get();

        return view('subscriptions.dashboard', compact('subscription', 'history'));
    }

    /**
     * Iniciar proceso de checkout
     */
    public function checkout(Request $request, $planSlug)
    {
        $plan = Plan::where('slug', $planSlug)->active()->firstOrFail();
        $tipo = $request->input('tipo', 'mensual'); // mensual o anual

        // Verificar si ya tiene una suscripción activa
        $currentSubscription = Auth::user()->activeSubscription;
        if ($currentSubscription) {
            return back()->with('error', 'Ya tienes una suscripción activa.');
        }

        return view('subscriptions.checkout', compact('plan', 'tipo'));
    }

    /**
     * Procesar pago con Stripe
     */
    public function processStripe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tipo' => 'required|in:mensual,anual',
            'payment_method_id' => 'required',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);
            $user = Auth::user();

            // Aquí irá la integración con Stripe
            // Por ahora creamos la suscripción directamente

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tipo' => $request->tipo,
                'estado' => 'activa',
                'metodo_pago' => 'stripe',
                'monto_pagado' => $request->tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual,
            ]);

            $subscription->activate();

            return redirect()->route('subscriptions.dashboard')
                ->with('success', '¡Suscripción activada exitosamente!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Procesar pago con PayPal
     */
    public function processPayPal(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tipo' => 'required|in:mensual,anual',
            'order_id' => 'required',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);
            $user = Auth::user();

            // Aquí irá la integración con PayPal
            // Por ahora creamos la suscripción directamente

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tipo' => $request->tipo,
                'estado' => 'activa',
                'metodo_pago' => 'paypal',
                'monto_pagado' => $request->tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual,
            ]);

            $subscription->activate();

            return redirect()->route('subscriptions.dashboard')
                ->with('success', '¡Suscripción activada exitosamente!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Crear suscripción en PayPal (llamado desde JavaScript)
     */
    public function createPayPalSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tipo' => 'required|in:mensual,anual',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);
            $tipo = $request->tipo;

            // Obtener el ID del plan de PayPal según el tipo
            $paypalPlanId = $tipo === 'mensual'
                ? $plan->paypal_plan_id_mensual
                : $plan->paypal_plan_id_anual;

            if (!$paypalPlanId) {
                return response()->json([
                    'success' => false,
                    'message' => 'El plan no está configurado en PayPal. Por favor contacta con soporte.'
                ], 400);
            }

            $paypalService = new \App\Services\PayPalService();

            // Crear la suscripción en PayPal
            $subscription = $paypalService->createSubscription(
                $paypalPlanId,
                route('subscriptions.paypal.success', ['plan_id' => $plan->id, 'tipo' => $tipo]),
                route('subscriptions.checkout', ['planSlug' => $plan->slug])
            );

            // Obtener la URL de aprobación
            $approvalUrl = null;
            foreach ($subscription['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription['id'],
                'approval_url' => $approvalUrl,
                'message' => 'Suscripción creada exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('PayPal subscription creation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la suscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activar suscripción de PayPal después de aprobación del usuario
     */
    public function activatePayPalSubscription(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required',
            'plan_id' => 'required|exists:plans,id',
            'tipo' => 'required|in:mensual,anual',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);
            $user = Auth::user();

            // Verificar con PayPal que la suscripción está activa
            $paypalService = new \App\Services\PayPalService();
            $paypalSubscription = $paypalService->getSubscription($request->subscription_id);

            if (!$paypalSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo verificar la suscripción con PayPal.'
                ], 400);
            }

            if ($paypalSubscription['status'] !== 'ACTIVE') {
                return response()->json([
                    'success' => false,
                    'message' => 'La suscripción no está activa en PayPal.'
                ], 400);
            }

            // Crear la suscripción en nuestra base de datos
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tipo' => $request->tipo,
                'estado' => 'activa',
                'metodo_pago' => 'paypal',
                'paypal_subscription_id' => $request->subscription_id,
                'monto_pagado' => $request->tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual,
            ]);

            $subscription->activate();

            // Enviar email de confirmación (solo si el email está configurado correctamente)
            try {
                if (config('mail.username') !== 'tu-email@gmail.com') {
                    $user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to send subscription notification email', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => '¡Suscripción activada exitosamente!',
                'redirect_url' => route('subscriptions.dashboard')
            ]);

        } catch (\Exception $e) {
            \Log::error('PayPal subscription activation error', [
                'message' => $e->getMessage(),
                'subscription_id' => $request->subscription_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al activar la suscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manejar retorno exitoso de PayPal
     */
    public function paypalSuccess(Request $request)
    {
        $subscriptionId = $request->query('subscription_id');
        $planId = $request->query('plan_id');
        $tipo = $request->query('tipo');

        if (!$subscriptionId || !$planId || !$tipo) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Información de suscripción incompleta.');
        }

        $plan = Plan::find($planId);

        return view('subscriptions.paypal-success', compact('subscriptionId', 'plan', 'tipo'));
    }

    /**
     * Cancelar suscripción
     * El usuario mantiene acceso hasta el fin del período ya pagado
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;

        if (!$subscription) {
            return back()->with('error', 'No tienes una suscripción activa.');
        }

        // Cancelar en PayPal si existe subscription_id
        if ($subscription->paypal_subscription_id) {
            // TODO: Integrar con PayPal SDK para cancelar la suscripción recurrente
            // $this->cancelPayPalSubscription($subscription->paypal_subscription_id);
        }

        // Cancelar la renovación automática pero mantener acceso hasta expiración
        $subscription->update([
            'estado' => 'cancelada_fin_periodo',
            'auto_renovacion' => false,
        ]);

        return back()->with('success', 'Tu suscripción ha sido cancelada. Seguirás teniendo acceso hasta el ' . $subscription->fecha_expiracion->format('d/m/Y') . '. No se realizará el siguiente cobro.');
    }

    /**
     * Reactivar suscripción
     */
    public function reactivate(Request $request)
    {
        $subscription = Auth::user()->subscriptions()
            ->where('estado', 'cancelada')
            ->latest()
            ->first();

        if (!$subscription) {
            return back()->with('error', 'No se encontró una suscripción para reactivar.');
        }

        $subscription->update([
            'auto_renovacion' => true,
        ]);

        return back()->with('success', 'Tu suscripción se renovará automáticamente.');
    }
}
