@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-brown mb-4">Aviso Legal</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
            <div class="prose prose-brown max-w-none">
                <section class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        El presente sitio web: <strong class="text-brown">www.citasmallorca.es</strong> es operado por <strong class="text-brown">Citas Mallorca S.L</strong> con domicilio en <strong>Carrer Gremi de Porgadors, 2 -2ºB, Nord, 07009 Palma, Illes Balears</strong>
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Puedes ponerte en contacto con nosotros a través del correo electrónico <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        El acceso y uso del Sitio Web atribuye la condición de usuario (en adelante, el "Usuario") e implica la aceptación plena y sin reservas de todas las disposiciones incluidas en este Aviso Legal. Si no estás de acuerdo con estas condiciones, por favor no utilices el Sitio Web.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Objeto del Sitio Web</h2>
                    <p class="text-gray-700 leading-relaxed">
                        El Sitio Web ofrece un servicio de contactos y encuentro entre personas adultas para fines de amistad, citas o relaciones personales.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Responsabilidad del Usuario</h2>
                    <p class="text-gray-700 leading-relaxed">
                        El Usuario se compromete a utilizar el Sitio Web de manera adecuada y lícita, sin incurrir en actividades que puedan ser consideradas ilegales, ofensivas, fraudulentas o que vulneren derechos de terceros.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Responsabilidad del Sitio Web</h2>
                    <p class="text-gray-700 leading-relaxed">
                        El proveedor del sitio no será responsable del uso que los usuarios hagan de la plataforma ni del contenido, veracidad o legalidad de la información proporcionada por ellos. Cada Usuario es responsable de la información que proporcione y de su comportamiento en la plataforma.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Propiedad Intelectual</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Todos los contenidos del Sitio Web, incluyendo textos, imágenes, logos y software, son propiedad de Citas Mallorca S.L o de terceros que han autorizado su uso. Queda prohibida la reproducción total o parcial sin autorización expresa.
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
