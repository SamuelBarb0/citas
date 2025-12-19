<x-guest-layout>
    <!-- Título -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-brown mb-2">Únete a Citas Mallorca</h2>
        <p class="text-gray-600">Crea tu cuenta y empieza a conocer gente increíble</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-brown mb-2">Nombre</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-semibold text-brown mb-2">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-semibold text-brown mb-2">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-semibold text-brown mb-2">Confirmar Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Botón de registro -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-lg shadow-lg">
                Crear Cuenta Gratis
            </button>
        </div>

        <!-- Link a login -->
        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-brown hover:text-heart-red font-semibold transition">
                    Inicia sesión aquí
                </a>
            </p>
        </div>

        <!-- Términos y condiciones -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Al registrarte, aceptas nuestros Términos de Uso y Política de Privacidad
            </p>
        </div>
    </form>
</x-guest-layout>
