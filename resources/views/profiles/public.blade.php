@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header fijo con glassmorphism -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ url()->previous() }}" class="flex items-center gap-2 text-brown hover:text-heart-red transition group">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </div>
                    <span class="font-semibold hidden sm:inline">Volver</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="text-brown hover:text-heart-red transition font-semibold text-sm">
                        Seguir explorando
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de feedback flotantes -->
    @if(session('success'))
        <div class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-bounce">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-bounce">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="fixed top-20 right-4 z-50 bg-blue-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-bounce">
            {{ session('info') }}
        </div>
    @endif

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Tarjeta de perfil moderna con hero image -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-6">
                <!-- Galería de fotos estilo Instagram Stories -->
                <div class="relative h-[500px] sm:h-[600px] lg:h-[700px]">
                    @php
                        $allPhotos = array_filter([
                            $profile->foto_principal,
                            ...($profile->fotos_adicionales ?? [])
                        ]);
                    @endphp

                    @if(count($allPhotos) > 0)
                        <!-- Contenedor de la galería -->
                        <div id="photo-gallery" class="relative w-full h-full overflow-hidden cursor-pointer">
                            @foreach($allPhotos as $index => $photo)
                                <div class="gallery-photo absolute inset-0 transition-opacity duration-300 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                                     data-index="{{ $index }}">
                                    <img src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}"
                                         alt="{{ $profile->nombre }}"
                                         class="w-full h-full object-cover"
                                         data-fullscreen-src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}">
                                </div>
                            @endforeach
                        </div>

                        @if(count($allPhotos) > 1)
                            <!-- Áreas táctiles invisibles estilo Instagram Stories -->
                            <div class="absolute inset-0 flex z-20 pointer-events-none">
                                <button id="prev-photo" class="w-1/2 h-full focus:outline-none active:bg-black/10 transition pointer-events-auto"></button>
                                <button id="next-photo" class="w-1/2 h-full focus:outline-none active:bg-black/10 transition pointer-events-auto"></button>
                            </div>

                            <!-- Indicadores de foto en la parte superior (barras estilo Stories) -->
                            <div class="absolute top-3 left-0 right-0 z-30 flex gap-1.5 px-3 pointer-events-none">
                                @foreach($allPhotos as $index => $photo)
                                    <div class="flex-1 h-1 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white shadow-lg' : 'bg-white/40' }}"
                                         data-indicator="{{ $index }}"></div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Botón de fullscreen en la esquina superior derecha -->
                        <button id="fullscreen-btn" class="absolute top-4 right-4 z-40 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition pointer-events-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </button>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-brown to-heart-red flex items-center justify-center">
                            <svg class="w-40 h-40 text-white/20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif

                    <!-- Gradiente oscuro en la parte inferior -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none z-10"></div>

                    <!-- Badge de ubicación -->
                    <div class="absolute top-6 left-6 z-30 pointer-events-none">
                        <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-heart-red" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-bold text-brown text-sm">{{ $profile->ciudad }}</span>
                        </div>
                    </div>

                    <!-- Badge de edad -->
                    <div class="absolute top-6 right-20 z-30 pointer-events-none">
                        <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                            <span class="font-bold text-brown text-lg">{{ $profile->edad }}</span>
                        </div>
                    </div>

                    <!-- Nombre en la parte inferior -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-30 pointer-events-none">
                        <h1 class="text-5xl sm:text-6xl font-black text-white drop-shadow-2xl mb-2">
                            {{ $profile->nombre }}
                        </h1>
                        <div class="flex items-center gap-2 text-white/90">
                            @if($profile->genero === 'hombre')
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">👨 Hombre</span>
                            @elseif($profile->genero === 'mujer')
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">👩 Mujer</span>
                            @else
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">👤 {{ ucfirst($profile->genero) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenido del perfil -->
                <div class="p-6 sm:p-8">
                    <!-- Sobre mí -->
                    @if($profile->biografia)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-brown">Sobre mí</h2>
                        </div>
                        <p class="text-gray-700 leading-relaxed text-base sm:text-lg pl-13">
                            {{ $profile->biografia }}
                        </p>
                    </div>
                    @endif

                    <!-- Intereses con diseño moderno -->
                    @if($profile->intereses && count($profile->intereses) > 0)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-brown to-yellow-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-brown">Mis Intereses</h2>
                        </div>
                        <div class="flex flex-wrap gap-3 pl-13">
                            @foreach($profile->intereses as $interes)
                                <span class="bg-gradient-to-r from-cream to-yellow-50 text-brown px-5 py-3 rounded-2xl font-bold text-sm shadow-md hover:shadow-lg transition border-2 border-brown/10">
                                    {{ ucfirst($interes) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Buscando -->
                    <div class="mb-8 bg-gradient-to-r from-cream to-cream-dark rounded-2xl p-6 border-2 border-brown/20">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-brown">Buscando</h2>
                        </div>
                        <p class="text-brown font-semibold text-lg pl-13">
                            @if($profile->busco === 'hombre')
                                👨 Hombres
                            @elseif($profile->busco === 'mujer')
                                👩 Mujeres
                            @else
                                👥 Hombres y Mujeres
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción modernos y grandes -->
            <div class="sticky bottom-6 bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Botón de Like -->
                    <form action="{{ route('like.store', $user->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="liked_user_id" value="{{ $user->id }}">
                        <button type="submit" class="w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-5 px-8 rounded-2xl hover:shadow-glow transition-all font-black text-lg flex items-center justify-center gap-3 group">
                            <svg class="w-7 h-7 group-hover:scale-125 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            Me Gusta
                        </button>
                    </form>

                    <!-- Botón de Mensaje (solo si hay match) -->
                    @php
                        $hasMatch = \App\Models\UserMatch::where(function($query) use ($user) {
                            $query->where('user_id_1', auth()->id())
                                  ->where('user_id_2', $user->id);
                        })->orWhere(function($query) use ($user) {
                            $query->where('user_id_1', $user->id)
                                  ->where('user_id_2', auth()->id());
                        })->exists();
                    @endphp

                    @if($hasMatch)
                        @php
                            $match = \App\Models\UserMatch::where(function($query) use ($user) {
                                $query->where('user_id_1', auth()->id())
                                      ->where('user_id_2', $user->id);
                            })->orWhere(function($query) use ($user) {
                                $query->where('user_id_1', $user->id)
                                      ->where('user_id_2', auth()->id());
                            })->first();
                        @endphp
                        <a href="{{ route('messages.show', $match->id) }}" class="w-full bg-white text-brown border-4 border-brown py-5 px-8 rounded-2xl hover:bg-brown hover:text-white transition-all font-black text-lg flex items-center justify-center gap-3 group">
                            <svg class="w-7 h-7 group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Enviar Mensaje
                        </a>
                    @else
                        <div class="w-full bg-gray-100 text-gray-400 py-5 px-8 rounded-2xl font-black text-lg flex items-center justify-center gap-3 cursor-not-allowed">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Match para chatear
                        </div>
                    @endif
                </div>

                <!-- Botones de reportar y bloquear -->
                <div class="mt-4 flex gap-2">
                    <button id="report-btn" class="flex-1 px-4 py-3 bg-yellow-50 text-yellow-700 rounded-xl font-semibold text-sm hover:bg-yellow-100 transition flex items-center justify-center gap-2 border border-yellow-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Reportar
                    </button>
                    <button id="block-btn" class="flex-1 px-4 py-3 bg-red-50 text-red-600 rounded-xl font-semibold text-sm hover:bg-red-100 transition flex items-center justify-center gap-2 border border-red-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Bloquear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reportar -->
    <div id="report-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-black text-brown">Reportar Usuario</h3>
                <button id="close-report-modal" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('report.store', $user->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-brown mb-2">Razón del reporte</label>
                    <select name="reason" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none">
                        <option value="">Selecciona una razón</option>
                        <option value="inapropiado">Contenido inapropiado</option>
                        <option value="spam">Spam o publicidad</option>
                        <option value="acoso">Acoso o intimidación</option>
                        <option value="suplantacion">Suplantación de identidad</option>
                        <option value="menor_edad">Menor de edad</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-bold text-brown mb-2">Descripción (opcional)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none" placeholder="Proporciona más detalles..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" id="cancel-report" class="flex-1 px-6 py-3 bg-gray-100 text-brown rounded-xl font-bold hover:bg-gray-200 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl font-bold hover:shadow-lg transition">
                        Enviar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Bloquear -->
    <div id="block-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-black text-brown">Bloquear Usuario</h3>
                <button id="close-block-modal" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mb-6">
                <p class="text-gray-600 mb-4">¿Estás seguro de que quieres bloquear a <strong>{{ $profile->nombre }}</strong>?</p>
                <ul class="text-sm text-gray-500 space-y-2 bg-gray-50 rounded-xl p-4">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        No volverás a ver su perfil
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Si tienen un match, se eliminará
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Puedes desbloquear más tarde
                    </li>
                </ul>
            </div>
            <form action="{{ route('block.store', $user->id) }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" id="cancel-block" class="flex-1 px-6 py-3 bg-gray-100 text-brown rounded-xl font-bold hover:bg-gray-200 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold hover:shadow-lg transition">
                        Bloquear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    .animate-bounce {
        animation: bounce 2s infinite;
    }
</style>

@if(isset($allPhotos) && count($allPhotos) > 1)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photos = document.querySelectorAll('.gallery-photo');
        const indicators = document.querySelectorAll('[data-indicator]');
        const prevBtn = document.getElementById('prev-photo');
        const nextBtn = document.getElementById('next-photo');
        let currentIndex = 0;

        function showPhoto(index) {
            // Ocultar todas las fotos
            photos.forEach(photo => {
                photo.classList.remove('opacity-100', 'z-10');
                photo.classList.add('opacity-0', 'z-0');
            });

            // Actualizar indicadores (barras)
            indicators.forEach(indicator => {
                indicator.classList.remove('bg-white', 'shadow-lg');
                indicator.classList.add('bg-white/40');
            });

            // Mostrar foto actual
            photos[index].classList.remove('opacity-0', 'z-0');
            photos[index].classList.add('opacity-100', 'z-10');

            // Actualizar indicador actual
            indicators[index].classList.remove('bg-white/40');
            indicators[index].classList.add('bg-white', 'shadow-lg');

            currentIndex = index;
        }

        // Navegación con botones (áreas táctiles invisibles)
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const newIndex = (currentIndex - 1 + photos.length) % photos.length;
                showPhoto(newIndex);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const newIndex = (currentIndex + 1) % photos.length;
                showPhoto(newIndex);
            });
        }

        // Navegación con teclado
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                const newIndex = (currentIndex - 1 + photos.length) % photos.length;
                showPhoto(newIndex);
            } else if (e.key === 'ArrowRight') {
                const newIndex = (currentIndex + 1) % photos.length;
                showPhoto(newIndex);
            }
        });

        // Soporte para swipe en móvil
        let touchStartX = 0;
        let touchEndX = 0;
        const gallery = document.getElementById('photo-gallery');

        gallery.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        gallery.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left - next photo
                const newIndex = (currentIndex + 1) % photos.length;
                showPhoto(newIndex);
            }
            if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right - previous photo
                const newIndex = (currentIndex - 1 + photos.length) % photos.length;
                showPhoto(newIndex);
            }
        }
    });
</script>
@endif

<script>
    // Funcionalidad de fullscreen para las fotos
    document.addEventListener('DOMContentLoaded', function() {
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const photos = document.querySelectorAll('.gallery-photo img');
        let currentFullscreenIndex = 0;

        if (fullscreenBtn && photos.length > 0) {
            fullscreenBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Obtener el índice de la foto actual
                const currentPhoto = document.querySelector('.gallery-photo.opacity-100');
                currentFullscreenIndex = parseInt(currentPhoto.dataset.index);

                openFullscreen(currentFullscreenIndex);
            });
        }

        function openFullscreen(index) {
            // Crear modal de fullscreen
            const modal = document.createElement('div');
            modal.id = 'fullscreen-modal';
            modal.className = 'fixed inset-0 bg-black z-[9999] flex items-center justify-center';

            modal.innerHTML = `
                <!-- Botón de cerrar -->
                <button id="close-fullscreen" class="absolute top-4 right-4 z-50 text-white hover:text-gray-300 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                ${photos.length > 1 ? `
                    <!-- Botón anterior -->
                    <button id="fullscreen-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 transition bg-black/30 hover:bg-black/50 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Botón siguiente -->
                    <button id="fullscreen-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 transition bg-black/30 hover:bg-black/50 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Indicadores -->
                    <div class="absolute bottom-8 left-0 right-0 z-50 flex justify-center gap-2">
                        ${Array.from(photos).map((_, i) => `
                            <div class="w-2 h-2 rounded-full transition-all ${i === index ? 'bg-white w-8' : 'bg-white/50'}" data-fs-indicator="${i}"></div>
                        `).join('')}
                    </div>
                ` : ''}

                <!-- Contenedor de imagen -->
                <div id="fullscreen-image-container" class="w-full h-full flex items-center justify-center p-4">
                    <img id="fullscreen-image"
                         src="${photos[index].dataset.fullscreenSrc}"
                         alt="Foto en pantalla completa"
                         class="max-w-full max-h-full object-contain">
                </div>
            `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            // Event listeners
            const closeBtn = document.getElementById('close-fullscreen');
            const prevBtn = document.getElementById('fullscreen-prev');
            const nextBtn = document.getElementById('fullscreen-next');
            const fsImage = document.getElementById('fullscreen-image');
            const fsIndicators = document.querySelectorAll('[data-fs-indicator]');

            function updateFullscreenPhoto(newIndex) {
                currentFullscreenIndex = newIndex;
                fsImage.src = photos[newIndex].dataset.fullscreenSrc;

                // Actualizar indicadores
                if (fsIndicators.length > 0) {
                    fsIndicators.forEach((indicator, i) => {
                        if (i === newIndex) {
                            indicator.className = 'w-8 h-2 rounded-full transition-all bg-white';
                        } else {
                            indicator.className = 'w-2 h-2 rounded-full transition-all bg-white/50';
                        }
                    });
                }
            }

            function closeFullscreen() {
                modal.remove();
                document.body.style.overflow = '';
            }

            // Cerrar
            closeBtn.addEventListener('click', closeFullscreen);
            modal.addEventListener('click', (e) => {
                if (e.target === modal || e.target.id === 'fullscreen-image-container') {
                    closeFullscreen();
                }
            });

            // Navegación
            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const newIndex = (currentFullscreenIndex - 1 + photos.length) % photos.length;
                    updateFullscreenPhoto(newIndex);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const newIndex = (currentFullscreenIndex + 1) % photos.length;
                    updateFullscreenPhoto(newIndex);
                });
            }

            // Teclado
            function handleKeydown(e) {
                if (e.key === 'Escape') {
                    closeFullscreen();
                    document.removeEventListener('keydown', handleKeydown);
                } else if (e.key === 'ArrowLeft' && photos.length > 1) {
                    const newIndex = (currentFullscreenIndex - 1 + photos.length) % photos.length;
                    updateFullscreenPhoto(newIndex);
                } else if (e.key === 'ArrowRight' && photos.length > 1) {
                    const newIndex = (currentFullscreenIndex + 1) % photos.length;
                    updateFullscreenPhoto(newIndex);
                }
            }

            document.addEventListener('keydown', handleKeydown);

            // Swipe en móvil
            let touchStartX = 0;
            let touchEndX = 0;

            modal.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            modal.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const swipeThreshold = 50;

                if (photos.length > 1) {
                    if (touchEndX < touchStartX - swipeThreshold) {
                        // Swipe left - next photo
                        const newIndex = (currentFullscreenIndex + 1) % photos.length;
                        updateFullscreenPhoto(newIndex);
                    } else if (touchEndX > touchStartX + swipeThreshold) {
                        // Swipe right - previous photo
                        const newIndex = (currentFullscreenIndex - 1 + photos.length) % photos.length;
                        updateFullscreenPhoto(newIndex);
                    }
                }
            }, { passive: true });
        }
    });
</script>

<script>
    // Modales de reportar y bloquear
    document.addEventListener('DOMContentLoaded', function() {
        const reportBtn = document.getElementById('report-btn');
        const blockBtn = document.getElementById('block-btn');
        const reportModal = document.getElementById('report-modal');
        const blockModal = document.getElementById('block-modal');
        const closeReportModal = document.getElementById('close-report-modal');
        const closeBlockModal = document.getElementById('close-block-modal');
        const cancelReport = document.getElementById('cancel-report');
        const cancelBlock = document.getElementById('cancel-block');

        function openModal(modal) {
            modal.classList.remove('hidden');
        }

        function closeModal(modal) {
            modal.classList.add('hidden');
        }

        if (reportBtn) {
            reportBtn.addEventListener('click', () => openModal(reportModal));
        }

        if (blockBtn) {
            blockBtn.addEventListener('click', () => openModal(blockModal));
        }

        if (closeReportModal) {
            closeReportModal.addEventListener('click', () => closeModal(reportModal));
        }

        if (closeBlockModal) {
            closeBlockModal.addEventListener('click', () => closeModal(blockModal));
        }

        if (cancelReport) {
            cancelReport.addEventListener('click', () => closeModal(reportModal));
        }

        if (cancelBlock) {
            cancelBlock.addEventListener('click', () => closeModal(blockModal));
        }

        // Cerrar al hacer clic fuera del modal
        reportModal?.addEventListener('click', (e) => {
            if (e.target === reportModal) closeModal(reportModal);
        });

        blockModal?.addEventListener('click', (e) => {
            if (e.target === blockModal) closeModal(blockModal);
        });
    });
</script>

@endsection
