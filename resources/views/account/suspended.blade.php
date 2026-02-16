<x-guest-layout>
    <!-- Icono -->
    <div class="text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        <!-- Titulo -->
        <h1 class="text-2xl font-black text-gray-800 mb-3">Cuenta Suspendida</h1>

        <!-- Mensaje -->
        <p class="text-gray-600 mb-6 leading-relaxed">
            Tu cuenta ha sido suspendida por un administrador debido a una posible violacion de nuestras normas de uso.
            Mientras tu cuenta este suspendida, no podras acceder a las funcionalidades de la plataforma.
        </p>

        <!-- Info adicional -->
        <div class="bg-amber-50 rounded-2xl p-4 mb-6 border border-amber-200">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-amber-800 text-left">
                    Si crees que esto es un error o deseas apelar la decision, puedes contactarnos por correo electronico y revisaremos tu caso.
                </p>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex flex-col gap-3">
            <a href="mailto:{{ \App\Models\SiteContent::get('contact_email', 'info@citasmallorca.es') }}"
               class="px-6 py-3 bg-brown text-white rounded-xl font-bold hover:bg-brown-dark transition text-center">
                Contactar Soporte
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
                    Cerrar Sesion
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
