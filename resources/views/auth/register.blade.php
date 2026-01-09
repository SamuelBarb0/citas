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

        <!-- Aceptación de Política de Privacidad y Términos -->
        <div class="mt-6 space-y-4">
            <!-- Política de Privacidad -->
            <div class="flex items-start gap-3 p-4 bg-cream rounded-xl border-2 border-gray-200">
                <label class="flex items-start gap-3 cursor-pointer w-full">
                    <input
                        type="checkbox"
                        id="accept-privacy-checkbox"
                        name="accept_privacy"
                        required
                        class="w-5 h-5 text-heart-red border-gray-300 rounded focus:ring-heart-red focus:ring-2 mt-1 flex-shrink-0"
                    >
                    <span class="text-gray-700 text-sm leading-relaxed">
                        He leído y acepto la
                        <a href="{{ route('legal.privacidad') }}" target="_blank" class="text-brown font-semibold hover:underline">
                            Política de Privacidad
                        </a>
                    </span>
                </label>
            </div>

            <!-- Términos y Condiciones -->
            <div class="flex items-start gap-3 p-4 bg-cream rounded-xl border-2 border-gray-200">
                <label class="flex items-start gap-3 cursor-pointer w-full">
                    <input
                        type="checkbox"
                        id="accept-terms-checkbox"
                        name="accept_terms"
                        required
                        class="w-5 h-5 text-heart-red border-gray-300 rounded focus:ring-heart-red focus:ring-2 mt-1 flex-shrink-0"
                    >
                    <span class="text-gray-700 text-sm leading-relaxed">
                        He leído y acepto los
                        <a href="{{ route('legal.terminos') }}" target="_blank" class="text-brown font-semibold hover:underline">
                            Términos y Condiciones de Uso
                        </a>
                    </span>
                </label>
            </div>

            <!-- Mensaje de Error si faltan checkboxes -->
            <div id="register-validation-error" class="hidden bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center gap-2 text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">Debes aceptar la Política de Privacidad y los Términos de Uso para continuar</span>
                </div>
            </div>
        </div>

        <!-- Botón de registro -->
        <div class="mt-6">
            <button type="submit" id="register-submit-btn" class="w-full bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-lg shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
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
    </form>

    <script>
        // Validación de checkboxes en tiempo real
        const privacyCheckbox = document.getElementById('accept-privacy-checkbox');
        const termsCheckbox = document.getElementById('accept-terms-checkbox');
        const submitButton = document.getElementById('register-submit-btn');
        const errorDiv = document.getElementById('register-validation-error');

        function checkValidation() {
            const privacyChecked = privacyCheckbox.checked;
            const termsChecked = termsCheckbox.checked;
            const allChecked = privacyChecked && termsChecked;

            // Habilitar/deshabilitar botón
            submitButton.disabled = !allChecked;

            // Mostrar/ocultar error
            if (!allChecked && (privacyCheckbox.classList.contains('was-clicked') || termsCheckbox.classList.contains('was-clicked'))) {
                errorDiv.classList.remove('hidden');
            } else {
                errorDiv.classList.add('hidden');
            }
        }

        // Agregar listeners
        privacyCheckbox.addEventListener('change', function() {
            this.classList.add('was-clicked');
            checkValidation();
        });

        termsCheckbox.addEventListener('change', function() {
            this.classList.add('was-clicked');
            checkValidation();
        });

        // Verificación inicial al cargar la página
        checkValidation();

        // Prevenir envío si no están marcados
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!privacyCheckbox.checked || !termsCheckbox.checked) {
                e.preventDefault();
                privacyCheckbox.classList.add('was-clicked');
                termsCheckbox.classList.add('was-clicked');
                errorDiv.classList.remove('hidden');
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</x-guest-layout>
