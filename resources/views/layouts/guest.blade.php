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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cream">
            <!-- Logo -->
            <div class="mb-6">
                <a href="/" class="flex items-center justify-center">
                    <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-24 h-24">
                </a>
            </div>

            <!-- Tarjeta de autenticación -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden rounded-3xl">
                {{ $slot }}
            </div>

            <!-- Link de vuelta -->
            <div class="mt-6">
                <a href="{{ url('/') }}" class="text-brown hover:text-heart-red transition text-sm font-medium">
                    ← Volver al inicio
                </a>
            </div>
        </div>
    </body>
</html>
