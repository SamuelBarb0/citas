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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-16 h-16">
                    </a>
                </div>

                <!-- Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    @guest
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
                    <button type="button" class="text-gray-700 hover:text-gray-900" id="mobile-menu-button">
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
                    <a href="{{ route('login') }}" class="block px-3 py-2 bg-heart-red text-white rounded-full text-center font-medium mx-2 shadow-lg">Entrar / Registrarse</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Descubrir</a>
                    <a href="{{ route('matches') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Mis Matches</a>
                    <a href="{{ route('messages') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Mensajes</a>
                    <a href="{{ route('user.profile.show') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Mi Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Cerrar Sesión</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-20 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo y descripción -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-20 h-20">
                    </div>
                    <p class="text-gray-600 mb-4">
                        Tu punto de encuentro para conocer gente auténtica en la isla.
                        Conversa sin complicaciones y pásatelo bien en Mallorca.
                    </p>
                </div>

                <!-- Enlaces -->
                <div>
                    <h3 class="font-semibold mb-4 text-brown">Empresa</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">Sobre Nosotros</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">Contacto</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">Blog</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="font-semibold mb-4 text-brown">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">Términos de Uso</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">Privacidad</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8 text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Citas Mallorca. Todos los derechos reservados.</p>
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
