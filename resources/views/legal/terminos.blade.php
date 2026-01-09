@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-brown mb-4">Términos y Condiciones de Uso</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
            <div class="prose prose-brown max-w-none">
                <section class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Bienvenido/a a <strong class="text-brown">Citas Mallorca</strong>. Al registrarte y utilizar nuestra plataforma, aceptas cumplir con los siguientes términos y condiciones.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Servicios Ofrecidos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Ofrecemos un servicio de contactos y citas para personas adultas (mayores de 18 años) que buscan conocer gente nueva en Mallorca y alrededores con fines de amistad, citas o relaciones personales.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Cuenta de Usuario</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Debes proporcionar información veraz al registrarte.</li>
                        <li>Eres responsable de mantener la confidencialidad de tu cuenta.</li>
                        <li>No puedes compartir o vender tu cuenta a terceros.</li>
                        <li>Nos reservamos el derecho de suspender o eliminar cuentas que incumplan nuestras políticas.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Código de Conducta</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">Está prohibido:</p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Subir contenido ofensivo, violento, pornográfico o ilegal.</li>
                        <li>Acosar, amenazar o agredir verbalmente a otros usuarios.</li>
                        <li>Crear perfiles falsos o usar identidades de terceros.</li>
                        <li>Usar la plataforma con fines comerciales o publicitarios no autorizados.</li>
                        <li>Intentar acceder de forma no autorizada a cuentas de otros usuarios.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Contenido Generado por el Usuario</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Tú eres el único responsable del contenido (fotos, textos, mensajes) que publiques. Al subir contenido, nos otorgas una licencia para mostrarlo en la plataforma, pero sigues siendo propietario del mismo.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Límite de Responsabilidad</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Citas Mallorca actúa como intermediario entre usuarios. No somos responsables de:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Encuentros o interacciones fuera de la plataforma.</li>
                        <li>Veracidad de la información proporcionada por usuarios.</li>
                        <li>Daños o perjuicios derivados del uso de la plataforma.</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        Recomendamos tomar precauciones al conocer gente online y reportar cualquier comportamiento sospechoso.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Suspensión y Cierre de Cuenta</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Nos reservamos el derecho de suspender o eliminar tu cuenta si detectamos incumplimiento de estos términos, conducta inapropiada o abuso del servicio.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Modificación de los Términos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Podemos actualizar estos términos en cualquier momento. Te notificaremos de cambios importantes. El uso continuado del servicio tras dichos cambios implica tu aceptación de los nuevos términos.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Contacto</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Si tienes preguntas sobre estos términos, escríbenos a
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
