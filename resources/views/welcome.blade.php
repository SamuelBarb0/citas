@extends('layouts.public')

@section('title', 'Citas Mallorca - Encuentra el amor en la isla')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Estilos personalizados para Select2 */
    .select2-container--default .select2-selection--single {
        height: 52px;
        border: 2px solid #d1d5db;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 52px;
        padding: 0;
        color: #374151;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #A67C52;
        box-shadow: 0 0 0 1px #A67C52;
    }

    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: #A67C52 !important;
    }
</style>
@endpush

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
                        <!-- Mi Género -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mi género</label>
                            <x-dynamic-select
                                tipo="genero"
                                name="genero"
                                :required="true"
                                placeholder="Selecciona..."
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition"
                            />
                        </div>

                        <!-- Mi Orientación Sexual (opcional) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mi orientación sexual <span class="text-xs text-gray-500">(opcional)</span></label>
                            <x-dynamic-select
                                tipo="orientacion_sexual"
                                name="orientacion_sexual"
                                :required="false"
                                placeholder="Selecciona..."
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition"
                            />
                        </div>

                        <!-- Busco -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Busco</label>
                            <x-dynamic-select
                                tipo="busco"
                                name="busco"
                                :required="true"
                                placeholder="Selecciona..."
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition"
                            />
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
                            <x-dynamic-select
                                tipo="ciudad"
                                name="ciudad"
                                id="ciudad-welcome"
                                :required="false"
                                placeholder="Busca tu ciudad..."
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition"
                                :use-select2="true"
                            />
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


<!-- Perfiles de usuarios (según REFERENCIA2) -->
<div class="relative bg-cream py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 text-brown">
                Conoce gente increíble
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Miles de personas en Mallorca ya están conectando. ¡Únete ahora!
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @forelse($perfiles as $perfil)
                <div class="profile-card bg-white rounded-2xl shadow-smooth overflow-hidden group cursor-pointer hover:shadow-glow transition-all duration-300">
                    <!-- Imagen de perfil -->
                    <div class="relative overflow-hidden aspect-[3/4]">
                        <img src="{{ $perfil->foto_principal }}" alt="{{ $perfil->nombre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <!-- Gradiente overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        <!-- Info sobre la imagen -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 text-white">
                            <h3 class="text-base font-bold drop-shadow-lg">{{ $perfil->nombre }}, {{ $perfil->edad }}</h3>
                            <p class="text-xs drop-shadow-md flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $perfil->ciudad }}
                            </p>
                        </div>

                        <!-- Badge de verificación si está verificado -->
                        @if($perfil->verificado)
                            <div class="absolute top-2 right-2 bg-blue-500 rounded-full p-1.5">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg">No hay perfiles disponibles en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Botón para ver más -->
        @if($perfiles->count() >= 20)
            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="inline-block gradient-button text-white px-8 py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-glow transition">
                    Ver más perfiles
                </a>
            </div>
        @endif
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

<!-- jQuery (requerido para Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')
