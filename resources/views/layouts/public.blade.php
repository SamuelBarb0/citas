<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Citas Mallorca - Encuentra el amor en la isla')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="antialiased">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-2 xs:px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-12 h-12 sm:w-16 sm:h-16">
                    </a>
                </div>

                <!-- Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    @guest
                        <a href="{{ route('subscriptions.index') }}" class="text-gray-700 hover:text-gray-900 transition font-medium">
                            Planes
                        </a>
                        <a href="{{ route('login') }}" class="bg-heart-red text-white px-6 py-2 rounded-full hover:opacity-90 transition font-medium shadow-lg">
                            Entrar / Registrarse
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 transition">Descubrir</a>
                        <a href="{{ route('matches') }}" class="text-gray-700 hover:text-gray-900 transition">Mis Matches</a>
                        <a href="{{ route('messages') }}" class="text-gray-700 hover:text-gray-900 transition">Mensajes</a>
                        <a href="{{ route('user.profile.show') }}" class="text-gray-700 hover:text-gray-900 transition">Mi Perfil</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900 transition">Cerrar Sesión</button>
                        </form>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-gray-900 p-2" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @guest
                    <a href="{{ route('subscriptions.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">Planes</a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 bg-heart-red text-white rounded-full text-center font-medium mx-2 shadow-lg text-sm">Entrar / Registrarse</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">Descubrir</a>
                    <a href="{{ route('matches') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">Mis Matches</a>
                    <a href="{{ route('messages') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">Mensajes</a>
                    <a href="{{ route('user.profile.show') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">Mi Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md text-sm">Cerrar Sesión</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Banner de Cookies (no mostrar en páginas legales) -->
    @if(!request()->is('politica-cookies') && !request()->is('politica-privacidad') && !request()->is('aviso-legal') && !request()->is('terminos-condiciones') && !request()->is('terminos-contratacion') && !request()->is('condiciones-pago'))
        @include('components.cookie-banner')
    @endif

    <!-- Footer -->
    <footer class="bg-white mt-12 sm:mt-20 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <!-- Logo y descripción -->
                <div class="col-span-2">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-16 h-16 sm:w-20 sm:h-20">
                    </div>
                    <p class="text-gray-600 mb-3 sm:mb-4 text-xs sm:text-sm">
                        {{ \App\Models\SiteContent::get('footer_description', 'Tu punto de encuentro para conocer gente auténtica en la isla. Conversa sin complicaciones y pásatelo bien en Mallorca.') }}
                    </p>
                    <div class="text-xs sm:text-sm text-gray-500">
                        <p class="font-semibold text-brown">{{ \App\Models\SiteContent::get('footer_company_name', 'Citas Mallorca S.L') }}</p>
                        <p class="mt-2">
                            <a href="mailto:{{ \App\Models\SiteContent::get('contact_email', 'info@citasmallorca.es') }}" class="text-heart-red hover:underline font-semibold text-xs sm:text-sm break-all">
                                {{ \App\Models\SiteContent::get('contact_email', 'info@citasmallorca.es') }}
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Información Legal -->
                <div>
                    <h3 class="font-semibold mb-3 sm:mb-4 text-brown text-sm sm:text-base">Legal</h3>
                    <ul class="space-y-1.5 sm:space-y-2">
                        <li>
                            <a href="{{ route('legal.aviso-legal') }}" class="text-gray-600 hover:text-heart-red transition text-xs sm:text-sm">
                                Aviso Legal
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.privacidad') }}" class="text-gray-600 hover:text-heart-red transition text-xs sm:text-sm">
                                Privacidad
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.cookies') }}" class="text-gray-600 hover:text-heart-red transition text-xs sm:text-sm">
                                Cookies
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Términos y Condiciones -->
                <div>
                    <h3 class="font-semibold mb-3 sm:mb-4 text-brown text-sm sm:text-base">Términos</h3>
                    <ul class="space-y-1.5 sm:space-y-2">
                        <li>
                            <a href="{{ route('legal.terminos') }}" class="text-gray-600 hover:text-heart-red transition text-xs sm:text-sm">
                                Uso
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.contract-terms') }}" class="text-gray-600 hover:text-heart-red transition text-xs sm:text-sm">
                                Contratación
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('legal.payment-conditions') }}" class="text-gray-600 hover:text-heart-red transition text-xs sm:text-sm">
                                Pago
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-6 sm:mt-8 pt-6 sm:pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <p class="text-gray-600 text-center sm:text-left text-xs sm:text-sm">
                        &copy; {{ date('Y') }} {{ \App\Models\SiteContent::get('footer_company_name', 'Citas Mallorca S.L') }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-2 sm:gap-4">
                        <a href="{{ route('legal.privacidad') }}" class="text-xs text-gray-500 hover:text-heart-red transition">
                            Privacidad
                        </a>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        <a href="{{ route('legal.cookies') }}" class="text-xs text-gray-500 hover:text-heart-red transition">
                            Cookies
                        </a>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        <a href="{{ route('legal.terminos') }}" class="text-xs text-gray-500 hover:text-heart-red transition">
                            Términos
                        </a>
                        <span class="text-gray-300 hidden sm:inline">|</span>
                        <button onclick="showCookieBanner()" class="text-xs text-gray-500 hover:text-heart-red transition">
                            Configurar Cookies
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
