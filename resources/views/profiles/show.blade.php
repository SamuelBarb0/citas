@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header fijo con glassmorphism -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-brown hover:text-heart-red transition group">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </div>
                    <span class="font-semibold hidden sm:inline">Descubrir</span>
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('user.profile.edit') }}" class="bg-gradient-to-r from-brown to-heart-red text-white px-4 sm:px-6 py-2 rounded-full hover:shadow-lg transition font-semibold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Editar Perfil</span>
                        <span class="sm:hidden">Editar</span>
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
            <!-- Verificacion del Perfil -->
            @php
                $hasPendingRequest = \App\Models\VerificationRequest::where('user_id', Auth::id())
                    ->where('estado', 'pendiente')
                    ->exists();
            @endphp

            @if(!$profile->verified && !$hasPendingRequest)
                <!-- No verificado - Call to action -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 shadow-lg">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-sm text-blue-800 font-medium">Verifica tu identidad para ganar confianza</p>
                        </div>
                        <a href="{{ route('verification.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-semibold transition text-sm whitespace-nowrap">
                            Verificar
                        </a>
                    </div>
                </div>
            @elseif($hasPendingRequest)
                <!-- Solicitud pendiente -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-6 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-sm text-yellow-800 font-medium">Verificacion en proceso - Tu solicitud esta siendo revisada (24-48h)</p>
                    </div>
                </div>
            @endif

            <!-- Tarjeta de perfil moderna con hero image -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-6">
                <!-- Galeria de fotos estilo Instagram Stories -->
                <div class="relative h-[500px] sm:h-[600px] lg:h-[700px]">
                    @php
                        $allPhotos = array_filter([
                            $profile->foto_principal,
                            ...($profile->fotos_adicionales ?? [])
                        ]);
                    @endphp

                    @if(count($allPhotos) > 0)
                        <!-- Contenedor de la galeria -->
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
                            <!-- Areas tactiles invisibles estilo Instagram Stories -->
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

                        <!-- Boton de fullscreen en la esquina superior derecha -->
                        <button id="fullscreen-btn" class="absolute top-4 right-4 z-40 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition pointer-events-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </button>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-brown to-heart-red flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-32 h-32 text-white/20 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <a href="{{ route('user.profile.edit') }}" class="bg-white text-brown px-6 py-3 rounded-full font-bold hover:shadow-lg transition inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Subir fotos
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Gradiente oscuro en la parte inferior -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none z-10"></div>

                    <!-- Badge de ubicacion -->
                    <div class="absolute top-6 left-6 z-30 pointer-events-none">
                        <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4 text-heart-red" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-bold text-brown text-sm">{{ $profile->ciudad }}</span>
                        </div>
                    </div>

                    <!-- Badge de verificacion y edad -->
                    <div class="absolute top-6 right-20 z-30 pointer-events-none flex items-center gap-2">
                        @if($profile->verified)
                            <div class="bg-green-500 text-white px-3 py-2 rounded-full shadow-lg flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-bold">Verificado</span>
                            </div>
                        @endif
                        <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                            <span class="font-bold text-brown text-lg">{{ $profile->edad }}</span>
                        </div>
                    </div>

                    <!-- Nombre en la parte inferior -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-30 pointer-events-none">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-5xl sm:text-6xl font-black text-white drop-shadow-2xl">
                                {{ $profile->nombre }}
                            </h1>
                            @if($profile->verified)
                                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 text-white/90">
                            @if($profile->genero === 'hombre')
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">Hombre</span>
                            @elseif($profile->genero === 'mujer')
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">Mujer</span>
                            @elseif($profile->genero === 'persona_no_binaria')
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">Persona no binaria</span>
                            @elseif($profile->genero === 'genero_fluido')
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">Genero fluido</span>
                            @else
                                <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">{{ ucfirst(str_replace('_', ' ', $profile->genero)) }}</span>
                            @endif
                            <span class="bg-{{ $profile->activo ? 'green' : 'gray' }}-500/80 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold border border-white/30">
                                {{ $profile->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contenido del perfil -->
                <div class="p-6 sm:p-8">
                    <!-- Sobre mi -->
                    @if($profile->biografia)
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-brown">Sobre mi</h2>
                        </div>
                        <p class="text-gray-700 leading-relaxed text-base sm:text-lg pl-13">
                            {{ $profile->biografia }}
                        </p>
                    </div>
                    @else
                    <div class="mb-8 bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-gray-500 mb-3">Aun no has escrito tu biografia</p>
                            <a href="{{ route('user.profile.edit') }}" class="text-heart-red font-semibold hover:underline">Escribir biografia</a>
                        </div>
                    </div>
                    @endif

                    <!-- Intereses con diseno moderno -->
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
                    @else
                    <div class="mb-8 bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <p class="text-gray-500 mb-3">Anade tus intereses para mejores coincidencias</p>
                            <a href="{{ route('user.profile.edit') }}" class="text-heart-red font-semibold hover:underline">Anadir intereses</a>
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
                                Hombres
                            @elseif($profile->busco === 'mujer')
                                Mujeres
                            @elseif($profile->busco === 'persona_no_binaria')
                                Personas no binarias
                            @elseif($profile->busco === 'genero_fluido')
                                Personas de genero fluido
                            @elseif($profile->busco === 'cualquiera')
                                Cualquiera
                            @else
                                {{ ucfirst(str_replace('_', ' ', $profile->busco)) }}
                            @endif
                        </p>
                    </div>

                    <!-- Estadisticas -->
                    @php
                        $matchCount = \App\Models\UserMatch::where('user_id_1', Auth::id())
                            ->orWhere('user_id_2', Auth::id())
                            ->count();
                        $likesReceived = \App\Models\UserLike::where('liked_user_id', Auth::id())->count();
                    @endphp
                    <div class="grid grid-cols-3 gap-4 py-6 border-t border-gray-200">
                        <div class="text-center bg-gradient-to-br from-heart-red/10 to-heart-red/5 rounded-2xl p-4">
                            <p class="text-3xl sm:text-4xl font-black text-heart-red">{{ $matchCount }}</p>
                            <p class="text-gray-600 text-sm font-semibold">Matches</p>
                        </div>
                        <div class="text-center bg-gradient-to-br from-pink-100 to-pink-50 rounded-2xl p-4">
                            <p class="text-3xl sm:text-4xl font-black text-pink-500">{{ $likesReceived }}</p>
                            <p class="text-gray-600 text-sm font-semibold">Me gusta</p>
                        </div>
                        <div class="text-center bg-gradient-to-br from-brown/10 to-brown/5 rounded-2xl p-4">
                            <p class="text-3xl sm:text-4xl font-black text-brown">{{ count($allPhotos) }}</p>
                            <p class="text-gray-600 text-sm font-semibold">Fotos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de accion -->
            <div class="sticky bottom-6 z-50 bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl p-6 border border-gray-200">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-2xl hover:shadow-glow transition-all font-black text-lg flex items-center justify-center gap-3 group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        Descubrir
                    </a>
                    <a href="{{ route('matches') }}" class="bg-white text-brown border-4 border-brown py-4 px-6 rounded-2xl hover:bg-brown hover:text-white transition-all font-black text-lg flex items-center justify-center gap-3 group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Matches
                    </a>
                </div>
            </div>

            <!-- Consejos -->
            <div class="mt-6 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                        <span class="text-lg">💡</span>
                    </div>
                    <h3 class="font-black text-brown text-lg">Consejos para tu perfil</h3>
                </div>
                <ul class="space-y-3 text-gray-600">
                    @if(count($allPhotos) < 3)
                    <li class="flex items-start gap-3 bg-yellow-50 p-3 rounded-xl">
                        <span class="text-yellow-500 text-lg">📸</span>
                        <span class="text-sm">Anade al menos 3 fotos para tener mas visitas</span>
                    </li>
                    @endif
                    @if(!$profile->biografia)
                    <li class="flex items-start gap-3 bg-blue-50 p-3 rounded-xl">
                        <span class="text-blue-500 text-lg">✍️</span>
                        <span class="text-sm">Una biografia interesante aumenta tus matches</span>
                    </li>
                    @endif
                    @if(!$profile->intereses || count($profile->intereses) < 3)
                    <li class="flex items-start gap-3 bg-purple-50 p-3 rounded-xl">
                        <span class="text-purple-500 text-lg">⭐</span>
                        <span class="text-sm">Completa todos tus intereses para mejores coincidencias</span>
                    </li>
                    @endif
                    @if(!$profile->verified)
                    <li class="flex items-start gap-3 bg-green-50 p-3 rounded-xl">
                        <span class="text-green-500 text-lg">✓</span>
                        <span class="text-sm">Verifica tu perfil para ganar mas confianza</span>
                    </li>
                    @endif
                    @if(count($allPhotos) >= 3 && $profile->biografia && $profile->intereses && count($profile->intereses) >= 3 && $profile->verified)
                    <li class="flex items-start gap-3 bg-green-50 p-3 rounded-xl">
                        <span class="text-green-500 text-lg">🎉</span>
                        <span class="text-sm font-semibold">Tu perfil esta completo! Sigue explorando para encontrar tu match</span>
                    </li>
                    @endif
                </ul>
            </div>
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
            photos.forEach(photo => {
                photo.classList.remove('opacity-100', 'z-10');
                photo.classList.add('opacity-0', 'z-0');
            });

            indicators.forEach(indicator => {
                indicator.classList.remove('bg-white', 'shadow-lg');
                indicator.classList.add('bg-white/40');
            });

            photos[index].classList.remove('opacity-0', 'z-0');
            photos[index].classList.add('opacity-100', 'z-10');

            indicators[index].classList.remove('bg-white/40');
            indicators[index].classList.add('bg-white', 'shadow-lg');

            currentIndex = index;
        }

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

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                const newIndex = (currentIndex - 1 + photos.length) % photos.length;
                showPhoto(newIndex);
            } else if (e.key === 'ArrowRight') {
                const newIndex = (currentIndex + 1) % photos.length;
                showPhoto(newIndex);
            }
        });

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
                const newIndex = (currentIndex + 1) % photos.length;
                showPhoto(newIndex);
            }
            if (touchEndX > touchStartX + swipeThreshold) {
                const newIndex = (currentIndex - 1 + photos.length) % photos.length;
                showPhoto(newIndex);
            }
        }
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const photos = document.querySelectorAll('.gallery-photo img');
        let currentFullscreenIndex = 0;

        if (fullscreenBtn && photos.length > 0) {
            fullscreenBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const currentPhoto = document.querySelector('.gallery-photo.opacity-100');
                currentFullscreenIndex = parseInt(currentPhoto.dataset.index);

                openFullscreen(currentFullscreenIndex);
            });
        }

        function openFullscreen(index) {
            const modal = document.createElement('div');
            modal.id = 'fullscreen-modal';
            modal.className = 'fixed inset-0 bg-black z-[9999] flex items-center justify-center';

            modal.innerHTML = `
                <button id="close-fullscreen" class="absolute top-4 right-4 z-50 text-white hover:text-gray-300 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                ${photos.length > 1 ? `
                    <button id="fullscreen-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 transition bg-black/30 hover:bg-black/50 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <button id="fullscreen-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 transition bg-black/30 hover:bg-black/50 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <div class="absolute bottom-8 left-0 right-0 z-50 flex justify-center gap-2">
                        ${Array.from(photos).map((_, i) => `
                            <div class="w-2 h-2 rounded-full transition-all ${i === index ? 'bg-white w-8' : 'bg-white/50'}" data-fs-indicator="${i}"></div>
                        `).join('')}
                    </div>
                ` : ''}

                <div id="fullscreen-image-container" class="w-full h-full flex items-center justify-center p-4">
                    <img id="fullscreen-image"
                         src="${photos[index].dataset.fullscreenSrc}"
                         alt="Foto en pantalla completa"
                         class="max-w-full max-h-full object-contain">
                </div>
            `;

            document.body.appendChild(modal);
            document.body.style.overflow = 'hidden';

            const closeBtn = document.getElementById('close-fullscreen');
            const prevBtn = document.getElementById('fullscreen-prev');
            const nextBtn = document.getElementById('fullscreen-next');
            const fsImage = document.getElementById('fullscreen-image');
            const fsIndicators = document.querySelectorAll('[data-fs-indicator]');

            function updateFullscreenPhoto(newIndex) {
                currentFullscreenIndex = newIndex;
                fsImage.src = photos[newIndex].dataset.fullscreenSrc;

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

            closeBtn.addEventListener('click', closeFullscreen);
            modal.addEventListener('click', (e) => {
                if (e.target === modal || e.target.id === 'fullscreen-image-container') {
                    closeFullscreen();
                }
            });

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
                        const newIndex = (currentFullscreenIndex + 1) % photos.length;
                        updateFullscreenPhoto(newIndex);
                    } else if (touchEndX > touchStartX + swipeThreshold) {
                        const newIndex = (currentFullscreenIndex - 1 + photos.length) % photos.length;
                        updateFullscreenPhoto(newIndex);
                    }
                }
            }, { passive: true });
        }
    });
</script>

@endsection
