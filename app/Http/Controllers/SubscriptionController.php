<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\PaymentLog;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // Verificar si ya tiene una suscripción activa
        $currentSubscription = Auth::user()->activeSubscription;
        if ($currentSubscription) {
            return back()->with('error', 'Ya tienes una suscripción activa.');
        }

        // Determinar el tipo basado en lo que el plan ofrece
        $tieneMensual = $plan->precio_mensual > 0;
        $tieneAnual = $plan->precio_anual > 0;

        // Si el plan es gratis, redirigir
        if (!$tieneMensual && !$tieneAnual) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Este plan no tiene precio configurado.');
        }

        // Determinar el tipo: usar el solicitado si está disponible, sino usar el que tenga
        $tipoSolicitado = $request->input('tipo');
        if ($tipoSolicitado === 'anual' && $tieneAnual) {
            $tipo = 'anual';
        } elseif ($tipoSolicitado === 'mensual' && $tieneMensual) {
            $tipo = 'mensual';
        } elseif ($tieneMensual) {
            $tipo = 'mensual';
        } else {
            $tipo = 'anual';
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
        Log::info('=== PAYPAL: INICIO CREAR SUSCRIPCIÓN ===', [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'no-auth',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_data' => $request->all()
        ]);

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'tipo' => 'required|in:mensual,anual',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);
            $tipo = $request->tipo;

            Log::info('PAYPAL: Plan encontrado', [
                'plan_id' => $plan->id,
                'plan_nombre' => $plan->nombre,
                'plan_slug' => $plan->slug,
                'tipo_solicitado' => $tipo,
                'precio_mensual' => $plan->precio_mensual,
                'precio_anual' => $plan->precio_anual,
                'paypal_plan_id_mensual' => $plan->paypal_plan_id_mensual,
                'paypal_plan_id_anual' => $plan->paypal_plan_id_anual
            ]);

            // Verificar que el plan tenga el precio del tipo solicitado
            $tieneMensual = $plan->precio_mensual > 0;
            $tieneAnual = $plan->precio_anual > 0;

            if ($tipo === 'mensual' && !$tieneMensual) {
                Log::warning('PAYPAL: Plan sin opción mensual', ['plan_id' => $plan->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Este plan no tiene opción de pago mensual.'
                ], 400);
            }

            if ($tipo === 'anual' && !$tieneAnual) {
                Log::warning('PAYPAL: Plan sin opción anual', ['plan_id' => $plan->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Este plan no tiene opción de pago anual.'
                ], 400);
            }

            // Obtener el ID del plan de PayPal según el tipo
            $paypalPlanId = $tipo === 'mensual'
                ? $plan->paypal_plan_id_mensual
                : $plan->paypal_plan_id_anual;

            if (!$paypalPlanId) {
                Log::error('PAYPAL: Plan no configurado en PayPal', [
                    'plan_id' => $plan->id,
                    'tipo' => $tipo
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'El plan no está configurado en PayPal. Por favor contacta con soporte.'
                ], 400);
            }

            Log::info('PAYPAL: Creando suscripción en PayPal API', [
                'paypal_plan_id' => $paypalPlanId,
                'return_url' => route('subscriptions.paypal.success', ['plan_id' => $plan->id, 'tipo' => $tipo]),
                'cancel_url' => route('subscriptions.checkout', ['planSlug' => $plan->slug])
            ]);

            $paypalService = new \App\Services\PayPalService();

            // Determinar el precio según el tipo
            $precio = $tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual;

            Log::info('PAYPAL: Precio a cobrar', [
                'tipo' => $tipo,
                'precio' => $precio
            ]);

            // Crear la suscripción en PayPal con el precio para mostrar en verificación bancaria
            $subscription = $paypalService->createSubscription(
                $paypalPlanId,
                route('subscriptions.paypal.success', ['plan_id' => $plan->id, 'tipo' => $tipo]),
                route('subscriptions.checkout', ['planSlug' => $plan->slug]),
                $precio
            );

            Log::info('PAYPAL: Suscripción creada en PayPal', [
                'paypal_subscription_id' => $subscription['id'] ?? 'N/A',
                'status' => $subscription['status'] ?? 'N/A',
                'links' => $subscription['links'] ?? []
            ]);

            // Obtener la URL de aprobación
            $approvalUrl = null;
            foreach ($subscription['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            Log::info('PAYPAL: URL de aprobación obtenida', [
                'approval_url' => $approvalUrl,
                'subscription_id' => $subscription['id']
            ]);

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription['id'],
                'approval_url' => $approvalUrl,
                'message' => 'Suscripción creada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('=== PAYPAL ERROR: CREAR SUSCRIPCIÓN ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
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
        Log::info('=== PAYPAL: INICIO ACTIVAR SUSCRIPCIÓN ===', [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'no-auth',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_data' => $request->all()
        ]);

        $request->validate([
            'subscription_id' => 'required',
            'plan_id' => 'required|exists:plans,id',
            'tipo' => 'required|in:mensual,anual',
        ]);

        try {
            $plan = Plan::findOrFail($request->plan_id);
            $user = Auth::user();

            Log::info('PAYPAL ACTIVAR: Usuario y plan identificados', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name,
                'plan_id' => $plan->id,
                'plan_nombre' => $plan->nombre,
                'plan_slug' => $plan->slug,
                'tipo' => $request->tipo,
                'paypal_subscription_id' => $request->subscription_id
            ]);

            // Verificar si ya existe esta suscripción (evitar duplicados)
            $existingSubscription = UserSubscription::where('paypal_subscription_id', $request->subscription_id)->first();
            if ($existingSubscription) {
                Log::info('PAYPAL ACTIVAR: Suscripción ya existe (duplicado evitado)', [
                    'paypal_subscription_id' => $request->subscription_id,
                    'existing_subscription_id' => $existingSubscription->id,
                    'existing_user_id' => $existingSubscription->user_id,
                    'existing_estado' => $existingSubscription->estado,
                    'existing_created_at' => $existingSubscription->created_at
                ]);
                return response()->json([
                    'success' => true,
                    'message' => '¡Tu suscripción ya está activa!',
                    'redirect_url' => route('subscriptions.dashboard')
                ]);
            }

            // Verificar con PayPal que la suscripción está activa
            Log::info('PAYPAL ACTIVAR: Consultando estado en PayPal API...', [
                'subscription_id' => $request->subscription_id
            ]);

            $paypalService = new \App\Services\PayPalService();
            $paypalSubscription = $paypalService->getSubscription($request->subscription_id);

            if (!$paypalSubscription) {
                Log::error('PAYPAL ACTIVAR ERROR: No se pudo obtener suscripción de PayPal', [
                    'subscription_id' => $request->subscription_id,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo verificar la suscripción con PayPal.'
                ], 400);
            }

            Log::info('PAYPAL ACTIVAR: Respuesta de PayPal API', [
                'subscription_id' => $request->subscription_id,
                'status' => $paypalSubscription['status'] ?? 'unknown',
                'plan_id' => $paypalSubscription['plan_id'] ?? 'N/A',
                'subscriber' => $paypalSubscription['subscriber'] ?? [],
                'billing_info' => $paypalSubscription['billing_info'] ?? [],
                'create_time' => $paypalSubscription['create_time'] ?? 'N/A',
                'start_time' => $paypalSubscription['start_time'] ?? 'N/A'
            ]);

            // Aceptar ACTIVE y APPROVED como estados válidos
            $validStatuses = ['ACTIVE', 'APPROVED'];
            $currentStatus = $paypalSubscription['status'] ?? 'unknown';

            if (!in_array($currentStatus, $validStatuses)) {
                Log::warning('PAYPAL ACTIVAR: Estado no válido', [
                    'subscription_id' => $request->subscription_id,
                    'status' => $currentStatus,
                    'valid_statuses' => $validStatuses
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'La suscripción no está activa en PayPal. Estado: ' . $currentStatus
                ], 400);
            }

            // Determinar el monto correcto basado en el tipo y lo que el plan ofrece
            $tipo = $request->tipo;
            if ($tipo === 'mensual' && $plan->precio_mensual > 0) {
                $montoPagado = $plan->precio_mensual;
            } elseif ($tipo === 'anual' && $plan->precio_anual > 0) {
                $montoPagado = $plan->precio_anual;
            } else {
                $montoPagado = $plan->precio_mensual > 0 ? $plan->precio_mensual : $plan->precio_anual;
            }

            Log::info('PAYPAL ACTIVAR: Creando suscripción en base de datos', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tipo' => $tipo,
                'monto_pagado' => $montoPagado,
                'paypal_subscription_id' => $request->subscription_id
            ]);

            // Crear la suscripción en nuestra base de datos
            // Calcular las fechas aquí para asegurar activación inmediata
            $duracionMeses = $tipo === 'anual' ? 12 : 1;

            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'tipo' => $tipo,
                'estado' => 'activa',
                'metodo_pago' => 'paypal',
                'paypal_subscription_id' => $request->subscription_id,
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

            Log::info('PAYPAL ACTIVAR: Suscripción creada y activada en BD', [
                'subscription_id' => $subscription->id,
                'fecha_inicio' => $subscription->fecha_inicio,
                'fecha_expiracion' => $subscription->fecha_expiracion,
                'estado' => $subscription->estado
            ]);

            Log::info('=== PAYPAL ACTIVAR: ÉXITO COMPLETO ===', [
                'subscription_id' => $subscription->id,
                'paypal_subscription_id' => $request->subscription_id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'plan' => $plan->nombre,
                'tipo' => $tipo,
                'monto' => $montoPagado,
                'fecha_inicio' => $subscription->fecha_inicio,
                'fecha_expiracion' => $subscription->fecha_expiracion
            ]);

            // Registrar el pago en payment_logs
            $payerEmail = $paypalSubscription['subscriber']['email_address'] ?? null;
            $payerName = isset($paypalSubscription['subscriber']['name'])
                ? trim(($paypalSubscription['subscriber']['name']['given_name'] ?? '') . ' ' . ($paypalSubscription['subscriber']['name']['surname'] ?? ''))
                : null;

            Log::info('PAYPAL ACTIVAR: Registrando en payment_logs', [
                'payer_email' => $payerEmail,
                'payer_name' => $payerName
            ]);

            PaymentLog::logSuccess([
                'user_id' => $user->id,
                'user_subscription_id' => $subscription->id,
                'plan_id' => $plan->id,
                'paypal_subscription_id' => $request->subscription_id,
                'paypal_plan_id' => $paypalSubscription['plan_id'] ?? null,
                'amount' => $montoPagado,
                'currency' => 'EUR',
                'payment_method' => 'paypal',
                'type' => 'subscription',
                'payer_email' => $payerEmail,
                'payer_name' => $payerName,
                'description' => "Suscripción {$tipo} al plan {$plan->nombre}",
                'paypal_response' => $paypalSubscription,
            ]);

            Log::info('PAYPAL ACTIVAR: Payment log registrado');

            // Enviar email de confirmación de bienvenida
            // IMPORTANTE: Intentar siempre enviar el email, solo fallar silenciosamente si hay error de config
            Log::info('PAYPAL ACTIVAR: Iniciando proceso de envío de email', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'plan' => $plan->nombre
            ]);

            try {
                // Verificar configuración de correo
                $mailHost = config('mail.mailers.smtp.host');
                $mailUsername = config('mail.mailers.smtp.username');
                $mailFromAddress = config('mail.from.address');

                Log::info('PAYPAL ACTIVAR: Configuración de correo detectada', [
                    'mail_host' => $mailHost,
                    'mail_username' => $mailUsername,
                    'mail_from' => $mailFromAddress,
                    'mail_mailer' => config('mail.default')
                ]);

                $mailConfigured = $mailHost !== 'smtp.mailgun.org' &&
                                  $mailUsername !== null &&
                                  $mailUsername !== 'tu-email@gmail.com';

                Log::info('PAYPAL ACTIVAR: Resultado verificación de configuración', [
                    'mail_configured' => $mailConfigured,
                    'host_check' => $mailHost !== 'smtp.mailgun.org',
                    'username_check' => $mailUsername !== null,
                    'username_not_default' => $mailUsername !== 'tu-email@gmail.com'
                ]);

                if ($mailConfigured) {
                    Log::info('PAYPAL ACTIVAR: Intentando enviar email...', [
                        'subscription_id' => $subscription->id,
                        'plan_id' => $subscription->plan_id,
                        'plan_nombre' => $plan->nombre
                    ]);

                    // Asegurar que la relación 'plan' esté cargada en la suscripción
                    $subscription->setRelation('plan', $plan);

                    $user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));

                    Log::info('PAYPAL ACTIVAR: ✅ Email de bienvenida enviado exitosamente', [
                        'user_email' => $user->email,
                        'plan' => $plan->nombre,
                        'to' => $user->email,
                        'from' => $mailFromAddress
                    ]);
                } else {
                    Log::warning('PAYPAL ACTIVAR: ⚠️ Email NO enviado - configuración de correo no válida', [
                        'user_email' => $user->email,
                        'mail_host' => $mailHost,
                        'mail_username' => $mailUsername,
                        'reason' => 'Configuración de correo no cumple con los requisitos'
                    ]);
                }
            } catch (\Exception $e) {
                // No bloquear la activación por error de email
                Log::error('PAYPAL ACTIVAR: ❌ Error enviando email de bienvenida (no bloquea activación)', [
                    'error' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => '¡Suscripción activada exitosamente!',
                'redirect_url' => route('subscriptions.dashboard')
            ]);

        } catch (\Exception $e) {
            Log::error('=== PAYPAL ACTIVAR ERROR: EXCEPCIÓN ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'subscription_id' => $request->subscription_id ?? 'N/A',
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
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
        Log::info('=== PAYPAL SUCCESS: Usuario retornó de PayPal ===', [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email ?? 'no-auth',
            'ip' => $request->ip(),
            'all_query_params' => $request->query(),
            'full_url' => $request->fullUrl()
        ]);

        $subscriptionId = $request->query('subscription_id');
        $planId = $request->query('plan_id');
        $tipo = $request->query('tipo');

        Log::info('PAYPAL SUCCESS: Parámetros extraídos', [
            'subscription_id' => $subscriptionId,
            'plan_id' => $planId,
            'tipo' => $tipo
        ]);

        if (!$subscriptionId || !$planId || !$tipo) {
            Log::error('PAYPAL SUCCESS ERROR: Parámetros incompletos', [
                'subscription_id' => $subscriptionId,
                'plan_id' => $planId,
                'tipo' => $tipo
            ]);
            return redirect()->route('subscriptions.index')
                ->with('error', 'Información de suscripción incompleta.');
        }

        $plan = Plan::find($planId);

        if (!$plan) {
            Log::error('PAYPAL SUCCESS ERROR: Plan no encontrado', [
                'plan_id' => $planId
            ]);
            return redirect()->route('subscriptions.index')
                ->with('error', 'Plan no encontrado.');
        }

        Log::info('PAYPAL SUCCESS: Mostrando página de confirmación', [
            'subscription_id' => $subscriptionId,
            'plan_nombre' => $plan->nombre,
            'tipo' => $tipo
        ]);

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
            ->where('estado', 'cancelada_fin_periodo')
            ->latest()
            ->first();

        if (!$subscription) {
            return back()->with('error', 'No se encontró una suscripción para reactivar.');
        }

        $subscription->update([
            'estado' => 'activa',
            'auto_renovacion' => true,
        ]);

        return back()->with('success', 'Tu suscripción se ha reactivado y se renovará automáticamente.');
    }

    /**
     * SOLO PARA PRUEBAS: Cancelar suscripción inmediatamente (sin acceso)
     * Este método elimina la suscripción completamente, dejando al usuario sin ninguna suscripción
     */
    public function forceCancel(Request $request)
    {
        $user = Auth::user();

        // Buscar cualquier suscripción activa o cancelada_fin_periodo
        $subscription = $user->subscriptions()
            ->whereIn('estado', ['activa', 'cancelada_fin_periodo'])
            ->where('fecha_expiracion', '>', now())
            ->latest()
            ->first();

        if (!$subscription) {
            return back()->with('error', 'No tienes una suscripción activa para cancelar.');
        }

        // Cancelar completamente y quitar acceso inmediato
        $subscription->update([
            'estado' => 'cancelada',
            'auto_renovacion' => false,
            'fecha_expiracion' => now()->subMinute(), // Expiró hace 1 minuto (para asegurar que now() > fecha_expiracion)
        ]);

        return back()->with('success', '🧪 [PRUEBA] Suscripción cancelada completamente. Has perdido acceso inmediato.');
    }
}
