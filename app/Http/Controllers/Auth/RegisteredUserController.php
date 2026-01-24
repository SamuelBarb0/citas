<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Guardar los parámetros de perfil en la sesión para usarlos después
        if ($request->has('genero')) {
            session(['profile_data' => $request->only(['genero', 'orientacion_sexual', 'busco', 'edad_min', 'edad_max', 'ciudad'])]);
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'accept_privacy' => ['required', 'accepted'],
            'accept_terms' => ['required', 'accepted'],
        ], [
            'accept_privacy.required' => 'Debes aceptar la Política de Privacidad para continuar.',
            'accept_privacy.accepted' => 'Debes aceptar la Política de Privacidad para continuar.',
            'accept_terms.required' => 'Debes aceptar los Términos y Condiciones para continuar.',
            'accept_terms.accepted' => 'Debes aceptar los Términos y Condiciones para continuar.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Enviar email de bienvenida
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Redirigir a crear perfil primero
        return redirect(route('user.profile.create'))
            ->with('success', '¡Cuenta creada! Ahora completa tu perfil para empezar a conocer gente.');
    }
}
