<x-guest-layout>
    <!-- Título -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-brown mb-2">¿Olvidaste tu contraseña?</h2>
        <p class="text-gray-600">No te preocupes, te enviaremos un enlace para restablecerla</p>
    </div>

    <div class="mb-6 text-sm text-gray-600 bg-cream p-4 rounded-lg">
        Introduce tu correo electrónico y te enviaremos un enlace para que puedas crear una nueva contraseña.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-brown mb-2">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Botón de envío -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-lg shadow-lg">
                Enviar Enlace de Restablecimiento
            </button>
        </div>

        <!-- Link de vuelta al login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-brown hover:text-heart-red font-medium text-sm transition">
                ← Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>
