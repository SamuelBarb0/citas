@extends('layouts.public')

@section('title', 'Citas Mallorca - Encuentra el amor en la isla')

@section('content')
<!-- Hero Section con formulario lateral (según REFERENCIA1 y REFERENCIA2) -->
<div class="relative overflow-hidden bg-cream">
    <!-- Elementos decorativos sutiles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-heart-red opacity-5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-brown opacity-5 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            <!-- Text Content - 7 columnas -->
            <div class="lg:col-span-7 fade-in">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                    <span class="text-brown">Citas, contactos</span>
                    <br>
                    <span class="text-brown">y amor en </span><span class="gradient-text">Mallorca</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-700 mb-8 leading-relaxed max-w-xl">
                    Encuentra gente con tus mismas ganas de compartir momentos en la isla
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <a href="{{ route('register') }}" class="gradient-button text-white px-8 py-4 rounded-full font-bold text-base text-center shadow-lg">
                        Crear mi perfil gratis
                    </a>
                    <a href="{{ route('login') }}" class="bg-white text-brown border-2 border-brown px-8 py-4 rounded-full font-bold text-base text-center hover:bg-brown hover:text-white transition">
                        Entrar mi perfil
                    </a>
                </div>
            </div>

            <!-- Formulario lateral - 5 columnas (según REFERENCIA) -->
            <div class="lg:col-span-5 fade-in" style="animation-delay: 0.2s;">
                <div class="bg-white rounded-3xl border-2 border-brown shadow-xl p-8 max-w-md mx-auto lg:mx-0">
                    <!-- Logo del formulario -->
                    <div class="text-center mb-6">
                        <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-24 h-24 mx-auto mb-4">
                        <h3 class="text-2xl font-bold text-brown mb-2">Crear mi perfil</h3>
                    </div>

                    <!-- Formulario de registro rápido -->
                    <form action="{{ route('register') }}" method="GET" class="space-y-4">
                        <!-- Busco -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Busco</label>
                            <select name="busco" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition">
                                <option>a un hombre</option>
                                <option>a una mujer</option>
                                <option>a ambos</option>
                            </select>
                        </div>

                        <!-- Edad -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Desde</label>
                                <select name="edad_min" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition">
                                    <option>18+</option>
                                    <option>25+</option>
                                    <option>30+</option>
                                    <option>40+</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Hasta</label>
                                <select name="edad_max" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition">
                                    <option>30</option>
                                    <option>40</option>
                                    <option>50</option>
                                    <option>60+</option>
                                </select>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Vivo en</label>
                            <select name="ciudad" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition">
                                <option>Mallorca</option>
                                <option>Palma</option>
                                <option>Alcúdia</option>
                                <option>Manacor</option>
                            </select>
                        </div>

                        <!-- Botón submit -->
                        <button type="submit" class="w-full gradient-button text-white py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-glow transition">
                            Empezar ahora
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de imágenes (I1, I2, I3) -->
<div class="bg-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Imagen 1 -->
            <div class="fade-in rounded-3xl overflow-hidden shadow-smooth group">
                <img src="{{ asset('images/I1.png') }}" alt="Conexiones en Mallorca" class="w-full h-96 object-cover group-hover:scale-105 transition-transform duration-500">
            </div>

            <!-- Imagen 2 -->
            <div class="fade-in rounded-3xl overflow-hidden shadow-smooth group" style="animation-delay: 0.1s;">
                <img src="{{ asset('images/I2.png') }}" alt="Amigos en Mallorca" class="w-full h-96 object-cover group-hover:scale-105 transition-transform duration-500">
            </div>

            <!-- Imagen 3 -->
            <div class="fade-in rounded-3xl overflow-hidden shadow-smooth group" style="animation-delay: 0.2s;">
                <img src="{{ asset('images/I3.png') }}" alt="Romance en Mallorca" class="w-full h-96 object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
        </div>
    </div>
</div>

<!-- Perfiles de usuarios (según REFERENCIA2) -->
<div class="relative bg-cream py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 text-brown">
                Perfiles de usuarios
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($perfiles as $perfil)
                <div class="profile-card bg-white rounded-3xl shadow-smooth overflow-hidden group cursor-pointer">
                    <!-- Imagen de perfil -->
                    <div class="relative overflow-hidden">
                        <img src="{{ $perfil->foto_principal }}" alt="{{ $perfil->nombre }}" class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500">

                        <!-- Gradiente overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </div>

                    <!-- Información del perfil -->
                    <div class="p-4 text-center">
                        <h3 class="text-lg font-bold text-brown">{{ $perfil->nombre }}, {{ $perfil->edad }}</h3>
                        <p class="text-sm text-gray-600">{{ $perfil->ciudad }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg">No hay perfiles disponibles en este momento.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Features Section - ¿Cómo funciona? -->
<div class="relative bg-white py-20 md:py-28">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 fade-in">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
                <span class="text-brown">¿Cómo</span> <span class="gradient-text">funciona?</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Feature 1 -->
            <div class="text-center group fade-in">
                <div class="bg-cream rounded-3xl w-28 h-28 flex items-center justify-center mx-auto mb-6 shadow-smooth group-hover:shadow-glow transition-all group-hover:scale-110 duration-300">
                    <span class="text-6xl">👤</span>
                </div>
                <div class="bg-gradient-to-br from-brown to-heart-red text-white rounded-full px-6 py-2 inline-block mb-4 font-bold">
                    Paso 1
                </div>
                <h3 class="text-2xl font-bold text-brown mb-4">Crea tu perfil</h3>
                <p class="text-gray-600 leading-relaxed">
                    Regístrate gratis y completa tu perfil con tus fotos y preferencias. Es rápido y sencillo.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center group fade-in" style="animation-delay: 0.1s;">
                <div class="bg-cream rounded-3xl w-28 h-28 flex items-center justify-center mx-auto mb-6 shadow-smooth group-hover:shadow-glow transition-all group-hover:scale-110 duration-300">
                    <span class="text-6xl">💕</span>
                </div>
                <div class="bg-gradient-to-br from-brown to-heart-red text-white rounded-full px-6 py-2 inline-block mb-4 font-bold">
                    Paso 2
                </div>
                <h3 class="text-2xl font-bold text-brown mb-4">Encuentra matches</h3>
                <p class="text-gray-600 leading-relaxed">
                    Descubre perfiles de personas auténticas en Mallorca. Dale like a quien te guste.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center group fade-in" style="animation-delay: 0.2s;">
                <div class="bg-cream rounded-3xl w-28 h-28 flex items-center justify-center mx-auto mb-6 shadow-smooth group-hover:shadow-glow transition-all group-hover:scale-110 duration-300">
                    <span class="text-6xl">💬</span>
                </div>
                <div class="bg-gradient-to-br from-brown to-heart-red text-white rounded-full px-6 py-2 inline-block mb-4 font-bold">
                    Paso 3
                </div>
                <h3 class="text-2xl font-bold text-brown mb-4">Conversa y conoce</h3>
                <p class="text-gray-600 leading-relaxed">
                    Cuando haya match, podrás chatear. Conoceos mejor y quedaos en persona.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Advertencias de Seguridad (ADVERTENCIAS.jpeg recreado con HTML/CSS) -->
<div class="bg-cream py-20 md:py-28">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
            <!-- Título principal -->
            <h2 class="text-3xl md:text-4xl font-extrabold text-brown mb-6">
                Consejos de seguridad<br>para tus interacciones
            </h2>

            <!-- Subtítulo -->
            <p class="text-lg md:text-xl text-gray-700 mb-8">
                En Citas Mallorca te recomendamos<br>cuidar tu privacidad y seguridad.
            </p>

            <!-- Lista de consejos -->
            <ul class="space-y-4 mb-10">
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-brown rounded-full mt-2"></div>
                    <p class="text-gray-700 text-base md:text-lg">
                        <strong>No compartas datos personales sensibles</strong> (dirección, documentos, números de tarjetas).
                    </p>
                </li>
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-brown rounded-full mt-2"></div>
                    <p class="text-gray-700 text-base md:text-lg">
                        <strong>Mantén la conversación dentro de la plataforma</strong> hasta sentir confianza.
                    </p>
                </li>
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-brown rounded-full mt-2"></div>
                    <p class="text-gray-700 text-base md:text-lg">
                        <strong>Si decides quedar, elige un lugar público</strong> y avisa a alguien de tu confianza.
                    </p>
                </li>
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-brown rounded-full mt-2"></div>
                    <p class="text-gray-700 text-base md:text-lg">
                        <strong>No aceptes presiones</strong> para enviar fotos privadas o dinero.
                    </p>
                </li>
                <li class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-brown rounded-full mt-2"></div>
                    <p class="text-gray-700 text-base md:text-lg">
                        <strong>Si notas comportamientos sospechosos,</strong> repórtalo de inmediato.
                    </p>
                </li>
            </ul>

            <!-- Mensaje de cierre -->
            <div class="border-t-2 border-gray-200 pt-8">
                <p class="text-xl md:text-2xl font-bold text-brown mb-4">
                    Tu bienestar es lo más importante.<br>Conecta con seguridad.
                </p>

                <div class="bg-cream rounded-2xl p-6 mt-6">
                    <p class="text-lg font-bold text-brown mb-2">
                        Reporta comportamientos sospechosos:
                    </p>
                    <p class="text-gray-700 text-sm md:text-base">
                        Si ves falta de respeto, presiones, chantajes o cualquier situación fraudulenta, ilegal, escríbenos a <strong>info@citasmallorca.es</strong>. Por favor envía capturas de pantalla y todas las pruebas que tengas a mano. Nuestro equipo actúa rápido para proteger a la comunidad.
                    </p>
                </div>

                <div class="text-center mt-8">
                    <p class="text-2xl md:text-3xl font-bold">
                        <span class="text-heart-red text-4xl">❤️</span> <span class="text-brown">Disfruta con responsabilidad</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección final motivacional (CITA.png) -->
<div class="bg-cream py-20 md:py-28">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in">
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-8 leading-tight" style="font-family: 'Georgia', serif; font-style: italic;">
            <span class="text-brown">Encuentra gente</span><br>
            <span class="text-brown">con tus mismas</span><br>
            <span class="gradient-text" style="font-style: italic;">ganas de compartir</span><br>
            <span class="gradient-text" style="font-style: italic;">momentos en</span><br>
            <span class="gradient-text" style="font-style: italic;">la isla.</span>
        </h2>
        <div class="text-8xl mt-8">
            ❤️
        </div>
    </div>
</div>
@endsection
