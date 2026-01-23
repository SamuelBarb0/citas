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
@php
    use App\Models\SiteContent;
@endphp
<!-- Hero Section con formulario lateral (según REFERENCIA1 y REFERENCIA2) -->
<div class="relative overflow-hidden bg-cream">
    <!-- Elementos decorativos sutiles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-heart-red opacity-5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-brown opacity-5 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-8 sm:py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-12 items-center">
            <!-- Text Content - 7 columnas -->
            <div class="lg:col-span-7 fade-in">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold mb-3 sm:mb-4 leading-tight">
                    <span class="text-brown">{{ SiteContent::get('hero_title_1', 'Citas, contactos') }}</span>
                    <br>
                    <span class="text-brown">{{ SiteContent::get('hero_title_2', 'y amor en') }} </span><span class="gradient-text">{{ SiteContent::get('hero_title_highlight', 'Mallorca') }}</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-700 mb-6 sm:mb-8 leading-relaxed max-w-xl">
                    {{ SiteContent::get('hero_subtitle', 'Encuentra gente con tus mismas ganas de compartir momentos en la isla') }}
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-6">
                    <a href="{{ route('register') }}" class="gradient-button text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-sm sm:text-base text-center shadow-lg">
                        {{ SiteContent::get('hero_btn_register', 'Crear mi perfil gratis') }}
                    </a>
                    <a href="{{ route('login') }}" class="bg-white text-brown border-2 border-brown px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-sm sm:text-base text-center hover:bg-brown hover:text-white transition">
                        {{ SiteContent::get('hero_btn_login', 'Entrar mi perfil') }}
                    </a>
                </div>
            </div>

            <!-- Formulario lateral - 5 columnas (según REFERENCIA) -->
            <div class="lg:col-span-5 fade-in" style="animation-delay: 0.2s;">
                <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brown shadow-xl p-5 sm:p-8 max-w-md mx-auto lg:mx-0">
                    <!-- Logo del formulario -->
                    <div class="text-center mb-4 sm:mb-6">
                        <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-16 h-16 sm:w-24 sm:h-24 mx-auto mb-3 sm:mb-4">
                        <h3 class="text-xl sm:text-2xl font-bold text-brown mb-2">{{ SiteContent::get('hero_form_title', 'Crear mi perfil') }}</h3>
                    </div>

                    <!-- Formulario de registro rápido -->
                    <form action="{{ route('register') }}" method="GET" class="space-y-3 sm:space-y-4">
                        <!-- Mi Género -->
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Mi género</label>
                            <x-dynamic-select
                                tipo="genero"
                                name="genero"
                                :required="true"
                                placeholder="Selecciona..."
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition text-sm"
                            />
                        </div>

                        <!-- Busco -->
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Busco</label>
                            <x-dynamic-select
                                tipo="busco"
                                name="busco"
                                :required="true"
                                placeholder="Selecciona..."
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition text-sm"
                            />
                        </div>

                        <!-- Edad con Slider Dual -->
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Rango de edad</label>
                            <div class="bg-gray-50 rounded-xl p-3 sm:p-4 border-2 border-gray-200">
                                <!-- Display de valores -->
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-lg sm:text-xl font-bold text-brown" id="edad-min-display">18</span>
                                    <span class="text-gray-400 text-sm">a</span>
                                    <span class="text-lg sm:text-xl font-bold text-heart-red" id="edad-max-display">99</span>
                                </div>
                                <!-- Slider container -->
                                <div class="relative h-2 mt-4 mb-2">
                                    <!-- Track background -->
                                    <div class="absolute inset-0 bg-gray-300 rounded-full"></div>
                                    <!-- Track active -->
                                    <div id="slider-track" class="absolute h-full bg-gradient-to-r from-brown to-heart-red rounded-full" style="left: 0%; right: 0%;"></div>
                                    <!-- Range inputs -->
                                    <input type="range" name="edad_min" id="edad-min-slider" min="18" max="99" value="18"
                                        class="absolute w-full h-2 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:bg-brown [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:bg-brown [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:shadow-lg [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-white">
                                    <input type="range" name="edad_max" id="edad-max-slider" min="18" max="99" value="99"
                                        class="absolute w-full h-2 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:bg-heart-red [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:shadow-lg [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:bg-heart-red [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:shadow-lg [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-white">
                                </div>
                                <!-- Labels -->
                                <div class="flex justify-between text-[10px] sm:text-xs text-gray-500 mt-1">
                                    <span>18</span>
                                    <span>99+</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">Vivo en</label>
                            <x-dynamic-select
                                tipo="ciudad"
                                name="ciudad"
                                id="ciudad-welcome"
                                :required="false"
                                placeholder="Busca tu ciudad..."
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-xl focus:border-brown focus:ring-0 transition text-sm"
                                :use-select2="true"
                            />
                        </div>

                        <!-- Botón submit -->
                        <button type="submit" class="w-full gradient-button text-white py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg shadow-lg hover:shadow-glow transition">
                            {{ SiteContent::get('hero_form_btn', 'Empezar ahora') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Perfiles de usuarios (según REFERENCIA2) -->
<div class="relative bg-cream py-10 sm:py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12 fade-in">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-extrabold mb-3 sm:mb-4 text-brown">
                {{ SiteContent::get('profiles_title', 'Conoce gente increíble') }}
            </h2>
            <p class="text-gray-600 text-sm sm:text-lg max-w-2xl mx-auto px-2">
                {{ SiteContent::get('profiles_subtitle', 'Miles de personas en Mallorca ya están conectando. ¡Únete ahora!') }}
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-4 md:gap-6">
            @forelse($perfiles as $perfil)
                <div class="profile-card bg-white rounded-xl sm:rounded-2xl shadow-smooth overflow-hidden group cursor-pointer hover:shadow-glow transition-all duration-300">
                    <!-- Imagen de perfil -->
                    <div class="relative overflow-hidden aspect-[3/4]">
                        <img src="{{ $perfil->foto_principal }}" alt="{{ $perfil->nombre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <!-- Gradiente overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        <!-- Info sobre la imagen -->
                        <div class="absolute bottom-0 left-0 right-0 p-2 sm:p-3 text-white">
                            <h3 class="text-xs sm:text-base font-bold drop-shadow-lg truncate">{{ $perfil->nombre }}, {{ $perfil->edad }}</h3>
                            <p class="text-[10px] sm:text-xs drop-shadow-md flex items-center gap-1 truncate">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $perfil->ciudad }}
                            </p>
                        </div>

                        <!-- Badge de verificación si está verificado -->
                        @if($perfil->verificado)
                            <div class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-blue-500 rounded-full p-1 sm:p-1.5">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
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
                    {{ SiteContent::get('profiles_btn_more', 'Ver más perfiles') }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Features Section - ¿Cómo funciona? -->
<div class="relative bg-white py-12 sm:py-20 md:py-28">
    <div class="relative max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-16 fade-in">
            <h2 class="text-2xl sm:text-4xl md:text-5xl font-extrabold mb-3 sm:mb-4">
                <span class="text-brown">¿Cómo</span> <span class="gradient-text">funciona?</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-10">
            <!-- Feature 1 -->
            <div class="text-center group fade-in">
                <div class="bg-cream rounded-2xl sm:rounded-3xl w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-smooth group-hover:shadow-glow transition-all group-hover:scale-110 duration-300">
                    <span class="text-4xl sm:text-6xl">{{ SiteContent::get('feature1_emoji', '👤') }}</span>
                </div>
                <div class="bg-gradient-to-br from-brown to-heart-red text-white rounded-full px-4 sm:px-6 py-1.5 sm:py-2 inline-block mb-3 sm:mb-4 font-bold text-xs sm:text-sm">
                    Paso 1
                </div>
                <h3 class="text-lg sm:text-2xl font-bold text-brown mb-2 sm:mb-4">{{ SiteContent::get('feature1_title', 'Crea tu perfil') }}</h3>
                <p class="text-gray-600 leading-relaxed text-sm sm:text-base px-4 sm:px-0">
                    {{ SiteContent::get('feature1_desc', 'Regístrate gratis y completa tu perfil con tus fotos y preferencias.') }}
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center group fade-in" style="animation-delay: 0.1s;">
                <div class="bg-cream rounded-2xl sm:rounded-3xl w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-smooth group-hover:shadow-glow transition-all group-hover:scale-110 duration-300">
                    <span class="text-4xl sm:text-6xl">{{ SiteContent::get('feature2_emoji', '💕') }}</span>
                </div>
                <div class="bg-gradient-to-br from-brown to-heart-red text-white rounded-full px-4 sm:px-6 py-1.5 sm:py-2 inline-block mb-3 sm:mb-4 font-bold text-xs sm:text-sm">
                    Paso 2
                </div>
                <h3 class="text-lg sm:text-2xl font-bold text-brown mb-2 sm:mb-4">{{ SiteContent::get('feature2_title', 'Encuentra matches') }}</h3>
                <p class="text-gray-600 leading-relaxed text-sm sm:text-base px-4 sm:px-0">
                    {{ SiteContent::get('feature2_desc', 'Descubre perfiles de personas auténticas en Mallorca.') }}
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center group fade-in" style="animation-delay: 0.2s;">
                <div class="bg-cream rounded-2xl sm:rounded-3xl w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-smooth group-hover:shadow-glow transition-all group-hover:scale-110 duration-300">
                    <span class="text-4xl sm:text-6xl">{{ SiteContent::get('feature3_emoji', '💬') }}</span>
                </div>
                <div class="bg-gradient-to-br from-brown to-heart-red text-white rounded-full px-4 sm:px-6 py-1.5 sm:py-2 inline-block mb-3 sm:mb-4 font-bold text-xs sm:text-sm">
                    Paso 3
                </div>
                <h3 class="text-lg sm:text-2xl font-bold text-brown mb-2 sm:mb-4">{{ SiteContent::get('feature3_title', 'Conversa y conoce') }}</h3>
                <p class="text-gray-600 leading-relaxed text-sm sm:text-base px-4 sm:px-0">
                    {{ SiteContent::get('feature3_desc', 'Cuando haya match, podrás chatear y quedar en persona.') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Advertencias de Seguridad (ADVERTENCIAS.jpeg recreado con HTML/CSS) -->
<div class="bg-cream py-12 sm:py-20 md:py-28">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl p-5 sm:p-8 md:p-12">
            <!-- Título principal -->
            <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold text-brown mb-4 sm:mb-6">
                {!! nl2br(e(SiteContent::get('safety_title', "Consejos de seguridad\npara tus interacciones"))) !!}
            </h2>

            <!-- Subtítulo -->
            <p class="text-sm sm:text-lg md:text-xl text-gray-700 mb-6 sm:mb-8">
                {!! nl2br(e(SiteContent::get('safety_subtitle', "En Citas Mallorca te recomendamos\ncuidar tu privacidad y seguridad."))) !!}
            </p>

            <!-- Lista de consejos -->
            <ul class="space-y-3 sm:space-y-4 mb-8 sm:mb-10">
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-brown rounded-full mt-1.5 sm:mt-2"></div>
                    <p class="text-gray-700 text-xs sm:text-base md:text-lg">
                        {{ SiteContent::get('safety_tip1', 'No compartas datos personales sensibles (dirección, documentos, números de tarjetas).') }}
                    </p>
                </li>
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-brown rounded-full mt-1.5 sm:mt-2"></div>
                    <p class="text-gray-700 text-xs sm:text-base md:text-lg">
                        {{ SiteContent::get('safety_tip2', 'Mantén la conversación dentro de la plataforma hasta sentir confianza.') }}
                    </p>
                </li>
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-brown rounded-full mt-1.5 sm:mt-2"></div>
                    <p class="text-gray-700 text-xs sm:text-base md:text-lg">
                        {{ SiteContent::get('safety_tip3', 'Si decides quedar, elige un lugar público y avisa a alguien.') }}
                    </p>
                </li>
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-brown rounded-full mt-1.5 sm:mt-2"></div>
                    <p class="text-gray-700 text-xs sm:text-base md:text-lg">
                        {{ SiteContent::get('safety_tip4', 'No aceptes presiones para enviar fotos privadas o dinero.') }}
                    </p>
                </li>
                <li class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-brown rounded-full mt-1.5 sm:mt-2"></div>
                    <p class="text-gray-700 text-xs sm:text-base md:text-lg">
                        {{ SiteContent::get('safety_tip5', 'Si notas comportamientos sospechosos, repórtalo de inmediato.') }}
                    </p>
                </li>
            </ul>

            <!-- Mensaje de cierre -->
            <div class="border-t-2 border-gray-200 pt-6 sm:pt-8">
                <p class="text-base sm:text-xl md:text-2xl font-bold text-brown mb-3 sm:mb-4">
                    {!! nl2br(e(SiteContent::get('safety_footer', "Tu bienestar es lo más importante.\nConecta con seguridad."))) !!}
                </p>

                <div class="bg-cream rounded-xl sm:rounded-2xl p-4 sm:p-6 mt-4 sm:mt-6">
                    <p class="text-sm sm:text-lg font-bold text-brown mb-2">
                        {{ SiteContent::get('safety_report_title', 'Reporta comportamientos sospechosos:') }}
                    </p>
                    <p class="text-gray-700 text-xs sm:text-sm md:text-base">
                        {{ SiteContent::get('safety_report_text', 'Si ves falta de respeto, presiones, chantajes o cualquier situación fraudulenta, escríbenos a info@citasmallorca.es. Nuestro equipo actúa rápido para proteger a la comunidad.') }}
                    </p>
                </div>

                <div class="text-center mt-6 sm:mt-8">
                    <p class="text-lg sm:text-2xl md:text-3xl font-bold">
                        <span class="text-heart-red text-2xl sm:text-4xl">❤️</span> <span class="text-brown">{{ SiteContent::get('safety_enjoy', 'Disfruta con responsabilidad') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección final motivacional (CITA.png) -->
<div class="bg-cream py-12 sm:py-20 md:py-28">
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 text-center fade-in">
        <h2 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-6 sm:mb-8 leading-tight" style="font-family: 'Georgia', serif; font-style: italic;">
            <span class="text-brown">{{ SiteContent::get('cta_line1', 'Encuentra gente') }}</span><br>
            <span class="text-brown">{{ SiteContent::get('cta_line2', 'con tus mismas') }}</span><br>
            <span class="gradient-text" style="font-style: italic;">{{ SiteContent::get('cta_line3', 'ganas de compartir') }}</span><br>
            <span class="gradient-text" style="font-style: italic;">{{ SiteContent::get('cta_line4', 'momentos en') }}</span><br>
            <span class="gradient-text" style="font-style: italic;">{{ SiteContent::get('cta_line5', 'la isla.') }}</span>
        </h2>
        <div class="text-5xl sm:text-8xl mt-6 sm:mt-8">
            ❤️
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- jQuery (requerido para Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dual Range Slider para edad
    const minSlider = document.getElementById('edad-min-slider');
    const maxSlider = document.getElementById('edad-max-slider');
    const minDisplay = document.getElementById('edad-min-display');
    const maxDisplay = document.getElementById('edad-max-display');
    const sliderTrack = document.getElementById('slider-track');

    function updateSlider() {
        const min = parseInt(minSlider.value);
        const max = parseInt(maxSlider.value);
        const minPercent = ((min - 18) / (99 - 18)) * 100;
        const maxPercent = ((max - 18) / (99 - 18)) * 100;

        // Actualizar display
        minDisplay.textContent = min;
        maxDisplay.textContent = max === 99 ? '99+' : max;

        // Actualizar track activo
        sliderTrack.style.left = minPercent + '%';
        sliderTrack.style.right = (100 - maxPercent) + '%';
    }

    minSlider.addEventListener('input', function() {
        const min = parseInt(minSlider.value);
        const max = parseInt(maxSlider.value);
        if (min > max) {
            minSlider.value = max;
        }
        updateSlider();
    });

    maxSlider.addEventListener('input', function() {
        const min = parseInt(minSlider.value);
        const max = parseInt(maxSlider.value);
        if (max < min) {
            maxSlider.value = min;
        }
        updateSlider();
    });

    // Inicializar
    updateSlider();
});
</script>
@endpush
