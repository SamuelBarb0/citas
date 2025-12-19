<x-guest-layout>
    <!-- Título -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-brown mb-2">Restablecer Contraseña</h2>
        <p class="text-gray-600">Crea una nueva contraseña segura para tu cuenta</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-brown mb-2">Correo Electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-semibold text-brown mb-2">Nueva Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-semibold text-brown mb-2">Confirmar Nueva Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Botón de restablecer -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-lg shadow-lg">
                Restablecer Contraseña
            </button>
        </div>
    </form>
</x-guest-layout>
