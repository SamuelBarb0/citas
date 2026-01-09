<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * Verifica que el usuario tenga una suscripción activa.
     * Si la suscripción está impagada, bloquea el acceso.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        $subscription = $user->activeSubscription;

        // Si tiene suscripción y está impagada, redirigir a página de pago
        if ($subscription && $subscription->isUnpaid()) {
            return redirect()->route('subscriptions.dashboard')
                ->with('error', 'Tu suscripción tiene un pago pendiente. Por favor, actualiza tu método de pago para continuar usando el servicio.');
        }

        return $next($request);
    }
}
