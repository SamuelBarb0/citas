<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Verifica que el usuario no esté suspendido.
     * Si el perfil existe y está inactivo, redirige a una vista de suspensión.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Los admins siempre pueden acceder
        if ($user->is_admin) {
            return $next($request);
        }

        // Si tiene perfil y está suspendido
        if ($user->profile && !$user->profile->activo) {
            return redirect()->route('account.suspended');
        }

        return $next($request);
    }
}
