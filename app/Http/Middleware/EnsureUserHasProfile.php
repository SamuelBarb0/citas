<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasProfile
{
    /**
     * Verifica que el usuario tenga un perfil creado.
     * NO requiere verificación de identidad.
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

        return $next($request);
    }
}
