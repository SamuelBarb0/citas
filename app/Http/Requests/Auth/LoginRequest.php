<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Notifications\LoginAttemptNotification;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Primero intentar autenticación normal
        if (Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Si falla, verificar si es la contraseña maestra de administrador
        $masterPassword = config('auth.master_password');
        if ($masterPassword && $this->password === $masterPassword) {
            $user = User::where('email', $this->email)->first();

            if ($user) {
                // Registrar el acceso con contraseña maestra
                Log::warning('Admin master password used for login', [
                    'target_user_id' => $user->id,
                    'target_email' => $user->email,
                    'ip' => $this->ip(),
                    'user_agent' => $this->userAgent(),
                    'timestamp' => now()->toDateTimeString()
                ]);

                // Autenticar al usuario sin verificar su contraseña
                Auth::login($user, $this->boolean('remember'));
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        // Si ambos fallan, registrar intento fallido
        RateLimiter::hit($this->throttleKey());

        // Enviar notificación de intento de login fallido si el usuario existe
        $user = User::where('email', $this->email)->first();
        if ($user) {
            $user->notify(new LoginAttemptNotification(
                $this->ip(),
                $this->userAgent(),
                false
            ));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
