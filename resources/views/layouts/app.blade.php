<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        {!! \App\Helpers\SeoHelper::generateMetaTags() !!}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-cream pb-16">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

            <!-- Banner de Cookies (no mostrar en páginas legales) -->
            @if(!request()->is('politica-cookies') && !request()->is('politica-privacidad') && !request()->is('aviso-legal') && !request()->is('terminos-condiciones') && !request()->is('terminos-contratacion') && !request()->is('condiciones-pago'))
                @include('components.cookie-banner')
            @endif

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-auto">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <!-- Links Legales -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Columna 1: Información Legal -->
                        <div>
                            <h3 class="text-sm font-semibold text-brown uppercase tracking-wider mb-3">Información Legal</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('legal.aviso-legal') }}" class="text-gray-600 hover:text-heart-red transition text-sm">
                                        Aviso Legal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('legal.privacidad') }}" class="text-gray-600 hover:text-heart-red transition text-sm">
                                        Política de Privacidad
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('legal.cookies') }}" class="text-gray-600 hover:text-heart-red transition text-sm">
                                        Política de Cookies
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Columna 2: Términos y Condiciones -->
                        <div>
                            <h3 class="text-sm font-semibold text-brown uppercase tracking-wider mb-3">Términos y Condiciones</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('legal.terminos') }}" class="text-gray-600 hover:text-heart-red transition text-sm">
                                        Términos de Uso
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('legal.contract-terms') }}" class="text-gray-600 hover:text-heart-red transition text-sm">
                                        Condiciones de Contratación
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('legal.payment-conditions') }}" class="text-gray-600 hover:text-heart-red transition text-sm">
                                        Condiciones de Pago y Cancelación
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Columna 3: Contacto -->
                        <div>
                            <h3 class="text-sm font-semibold text-brown uppercase tracking-wider mb-3">Contacto</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>
                                    <strong class="text-brown">{{ \App\Models\SiteContent::get('footer_company_name', 'Citas Mallorca S.L') }}</strong>
                                </li>
                                <li class="pt-2">
                                    <a href="mailto:{{ \App\Models\SiteContent::get('contact_email', 'info@citasmallorca.es') }}" class="text-heart-red hover:underline font-semibold">
                                        {{ \App\Models\SiteContent::get('contact_email', 'info@citasmallorca.es') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Separador -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <p class="text-sm text-gray-500 text-center md:text-left">
                                &copy; {{ date('Y') }} {{ \App\Models\SiteContent::get('footer_company_name', 'Citas Mallorca S.L') }}. Todos los derechos reservados.
                            </p>
                            <div class="flex space-x-4 mt-4 md:mt-0">
                                <a href="{{ route('legal.privacidad') }}" class="text-xs text-gray-500 hover:text-heart-red transition">
                                    Privacidad
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('legal.cookies') }}" class="text-xs text-gray-500 hover:text-heart-red transition">
                                    Cookies
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('legal.terminos') }}" class="text-xs text-gray-500 hover:text-heart-red transition">
                                    Términos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
