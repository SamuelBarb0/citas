<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no hay usuario autenticado, dejar pasar (auth middleware se encarga)
        if (!$user) {
            return $next($request);
        }

        // Si es admin, permitir acceso total
        if ($user->is_admin) {
            return $next($request);
        }

        // Verificar si tiene perfil
        if (!$user->profile) {
            return redirect()->route('user.profile.create')
                ->with('info', 'Debes completar tu perfil primero');
        }

        // Verificar si está verificado
        if (!$user->profile->verified) {
            // Verificar si ya tiene una solicitud pendiente
            $hasPendingRequest = \App\Models\VerificationRequest::where('user_id', $user->id)
                ->where('estado', 'pendiente')
                ->exists();

            if ($hasPendingRequest) {
                // Tiene solicitud pendiente, mostrar mensaje de espera
                return redirect()->route('verification.create')
                    ->with('info', 'Tu solicitud de verificación está siendo revisada. Te notificaremos pronto.');
            }

            // No tiene solicitud pendiente, debe crear una
            return redirect()->route('verification.create')
                ->with('error', 'Debes verificar tu identidad para poder usar la aplicación');
        }

        return $next($request);
    }
}
