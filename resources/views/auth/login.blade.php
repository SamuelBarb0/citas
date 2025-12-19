<x-guest-layout>
    <!-- Título -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-brown mb-2">Bienvenido de nuevo</h2>
        <p class="text-gray-600">Inicia sesión para continuar tu búsqueda del amor</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-brown mb-2">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-semibold text-brown mb-2">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-heart-red shadow-sm focus:ring-heart-red" name="remember">
                <span class="ms-2 text-sm text-gray-600">Recuérdame</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brown hover:text-heart-red transition font-medium" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Botón de login -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-lg shadow-lg">
                Iniciar Sesión
            </button>
        </div>

        <!-- Link a registro -->
        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-brown hover:text-heart-red font-semibold transition">
                    Regístrate gratis
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
