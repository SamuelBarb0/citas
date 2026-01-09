@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-brown mb-4">Política de Cookies</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
            <div class="prose prose-brown max-w-none">
                <section class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        En <strong class="text-brown">Citas Mallorca</strong> utilizamos cookies propias y de terceros para mejorar tu experiencia en el sitio, analizar el uso del mismo y ofrecerte contenido personalizado.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">¿Qué son las cookies?</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Las cookies son pequeños archivos de texto que se almacenan en tu navegador para recordar información sobre tu visita (preferencias, sesión activa, etc.).
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Tipos de Cookies que Utilizamos</h2>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-brown mb-2">Cookies Técnicas (Necesarias)</h3>
                        <p class="text-gray-700 leading-relaxed mb-2">
                            Son imprescindibles para el funcionamiento del sitio web. Permiten la navegación, gestionar tu sesión o mantener tu cuenta iniciada.
                        </p>
                        <p class="text-gray-700 leading-relaxed italic">
                            Estas cookies no requieren tu consentimiento ya que son esenciales para usar la plataforma.
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-brown mb-2">Cookies Analíticas</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Nos permiten analizar el uso del sitio web para medir, mejorar y optimizar nuestra web.
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-brown mb-2">Cookies de Terceros</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Utilizadas por proveedores externos como PayPal, Google Analytics, u otros servicios de pago o análisis.
                        </p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Cómo Gestionar las Cookies</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Puedes aceptar, rechazar o configurar las cookies desde el banner que aparece al entrar. También puedes configurarlas desde los ajustes de tu navegador:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li><a href="https://support.google.com/chrome/answer/95647?hl=es" target="_blank" class="text-heart-red hover:underline">Google Chrome</a></li>
                        <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank" class="text-heart-red hover:underline">Mozilla Firefox</a></li>
                        <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" class="text-heart-red hover:underline">Safari</a></li>
                        <li><a href="https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" class="text-heart-red hover:underline">Microsoft Edge</a></li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Más Información</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Si tienes dudas sobre esta política de cookies, contacta con nosotros en
                        <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>.
                    </p>
                </section>
            </div>
        </div>

        <!-- Botón Volver -->
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" class="text-brown hover:text-heart-red font-semibold transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>
    </div>
</div>
@endsection
