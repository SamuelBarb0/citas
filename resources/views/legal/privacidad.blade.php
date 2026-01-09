@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-brown mb-4">Política de Privacidad</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
            <div class="prose prose-brown max-w-none">
                <section class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        En <strong class="text-brown">Citas Mallorca</strong> nos tomamos muy en serio la protección de tus datos personales. Esta política explica cómo recopilamos, tratamos y protegemos tu información.
                    </p>
                    <div class="bg-brown/5 rounded-xl p-4 mb-4">
                        <p class="text-gray-700"><strong>Responsable del Tratamiento:</strong> Citas Mallorca</p>
                        <p class="text-gray-700"><strong>Email:</strong> <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline">info@citasmallorca.es</a></p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Datos que Recopilamos</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">Podemos recopilar los siguientes datos personales:</p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Nombre, apellidos y alias o nombre de usuario</li>
                        <li>Email</li>
                        <li>Fecha de nacimiento</li>
                        <li>Género e información de preferencias</li>
                        <li>Fotografías u otros contenidos subidos por el Usuario</li>
                        <li>Información de navegación (cookies, IP, datos técnicos)</li>
                        <li>Y demás datos que el usuario facilite voluntariamente</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Finalidades del Tratamiento</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">Tus datos serán utilizados para:</p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Prestar el servicio de contactos y permitir la interacción entre usuarios</li>
                        <li>Gestionar tu cuenta</li>
                        <li>Enviarte comunicaciones relacionadas con el servicio</li>
                        <li>Mantener la seguridad de la plataforma</li>
                        <li>Cumplir obligaciones legales</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Legitimación</h2>
                    <p class="text-gray-700 leading-relaxed">
                        El tratamiento se realiza basado en tu consentimiento, en la ejecución del contrato (prestación del servicio) y en el interés legítimo para garantizar la seguridad.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Conservación de los Datos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Conservaremos tus datos mientras seas usuario. Podrás solicitar la eliminación en cualquier momento.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Cesión de Datos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        No cederemos tus datos a terceros salvo obligación legal o para la prestación del servicio (por ejemplo, proveedores tecnológicos bajo contratos de confidencialidad).
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Derechos del Usuario</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Puedes ejercer tus derechos de acceso, rectificación, supresión, oposición, portabilidad y limitación enviando un email a <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>.
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
