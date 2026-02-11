@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cream pb-20">
    {{-- Notificaciones temporales --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-4 right-4 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @php
        $allPhotos = array_filter([
            $profile->foto_principal,
            ...($profile->fotos_adicionales ?? [])
        ]);
        $user = Auth::user();
        $hasPendingRequest = \App\Models\VerificationRequest::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->exists();
        $likesReceived = \App\Models\Like::where('liked_user_id', $user->id)->count();
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-5">

        {{-- ===== TARJETA DE PERFIL PRINCIPAL ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-start gap-5">
                    {{-- Foto de perfil --}}
                    <div class="relative flex-shrink-0">
                        @if($profile->foto_principal)
                            <img src="{{ str_starts_with($profile->foto_principal, 'http') ? $profile->foto_principal : Storage::url($profile->foto_principal) }}"
                                 alt="{{ $profile->nombre }}"
                                 class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl object-cover shadow-md">
                        @else
                            <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-brown to-heart-red flex items-center justify-center shadow-md">
                                <svg class="w-14 h-14 text-white/40" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Badge verificado --}}
                        @if($profile->verified)
                            <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-1 shadow-md border-2 border-white">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info del perfil --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h1 class="text-2xl sm:text-3xl font-black text-brown truncate">{{ $profile->nombre }}</h1>
                            <span class="text-xl sm:text-2xl font-bold text-brown/60">{{ $profile->edad }}</span>
                        </div>

                        <div class="flex items-center gap-1.5 text-gray-500 text-sm mb-3">
                            <svg class="w-4 h-4 text-heart-red flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ $profile->ciudad }}</span>
                            <span class="mx-1 text-gray-300">|</span>
                            <span class="bg-{{ $profile->activo ? 'green' : 'gray' }}-100 text-{{ $profile->activo ? 'green' : 'gray' }}-700 px-2 py-0.5 rounded-full text-xs font-semibold">
                                {{ $profile->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        {{-- Estadisticas compactas --}}
                        <div class="flex items-center gap-4 text-sm">
                            <div class="text-center">
                                <span class="block text-lg font-black text-heart-red">{{ $matchCount }}</span>
                                <span class="text-gray-500 text-xs">Matches</span>
                            </div>
                            <div class="w-px h-8 bg-gray-200"></div>
                            <div class="text-center">
                                <span class="block text-lg font-black text-pink-500">{{ $likesReceived }}</span>
                                <span class="text-gray-500 text-xs">Likes</span>
                            </div>
                            <div class="w-px h-8 bg-gray-200"></div>
                            <div class="text-center">
                                <span class="block text-lg font-black text-brown">{{ count($allPhotos) }}</span>
                                <span class="text-gray-500 text-xs">Fotos</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Boton editar perfil --}}
                <a href="{{ route('user.profile.edit') }}"
                   class="mt-4 w-full block text-center bg-gradient-to-r from-brown to-heart-red text-white py-2.5 rounded-xl font-semibold text-sm hover:shadow-lg transition">
                    Editar perfil
                </a>
            </div>
        </div>

        {{-- Verificacion pendiente --}}
        @if(!$profile->verified && !$hasPendingRequest)
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-sm text-blue-800 font-medium">Verifica tu perfil para ganar confianza</p>
                </div>
                <a href="{{ route('verification.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-semibold transition text-xs whitespace-nowrap">
                    Verificar
                </a>
            </div>
        @elseif($hasPendingRequest)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm text-yellow-800 font-medium">Verificacion en proceso (24-48h)</p>
            </div>
        @endif

        {{-- ===== MIS FOTOS ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-brown">Mis Fotos</h2>
                    <a href="{{ route('user.profile.edit') }}" class="text-heart-red text-sm font-semibold hover:underline">Gestionar</a>
                </div>

                @if(count($allPhotos) > 0)
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($allPhotos as $index => $photo)
                            <div class="relative aspect-square rounded-xl overflow-hidden cursor-pointer group" onclick="openFullscreen({{ $index }})">
                                <img src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}"
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     data-fullscreen-src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}">
                                @if($index === 0)
                                    <div class="absolute bottom-1 left-1 bg-brown/80 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                        Principal
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        {{-- Boton para anadir mas fotos --}}
                        @php $maxFotos = $user->getMaxFotosAdicionales() + 1; @endphp
                        @if(count($allPhotos) < $maxFotos)
                            <a href="{{ route('user.profile.edit') }}" class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center hover:border-heart-red hover:bg-red-50 transition group">
                                <svg class="w-8 h-8 text-gray-300 group-hover:text-heart-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                @else
                    <a href="{{ route('user.profile.edit') }}" class="block text-center py-8 border-2 border-dashed border-gray-300 rounded-xl hover:border-heart-red transition">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-400 text-sm font-medium">Sube tus fotos</p>
                    </a>
                @endif
            </div>
        </div>

        {{-- ===== SOBRE MI ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-black text-brown">Sobre mi</h2>
                    <a href="{{ route('user.profile.edit') }}" class="text-heart-red text-sm font-semibold hover:underline">Editar</a>
                </div>
                @if($profile->biografia)
                    <p class="text-gray-700 leading-relaxed text-sm">{{ $profile->biografia }}</p>
                @else
                    <p class="text-gray-400 text-sm italic">Aun no has escrito tu biografia.
                        <a href="{{ route('user.profile.edit') }}" class="text-heart-red font-semibold hover:underline">Escribe una</a>
                    </p>
                @endif

                {{-- Buscando --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-heart-red flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-600">Buscando:</span>
                    <span class="font-semibold text-brown">
                        @if($profile->busco === 'hombre') Hombres
                        @elseif($profile->busco === 'mujer') Mujeres
                        @elseif($profile->busco === 'persona_no_binaria') Personas no binarias
                        @elseif($profile->busco === 'genero_fluido') Personas de genero fluido
                        @elseif($profile->busco === 'cualquiera') Cualquiera
                        @else {{ ucfirst(str_replace('_', ' ', $profile->busco)) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ===== MENSAJES RECIENTES ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-brown">Mensajes</h2>
                    <a href="{{ route('messages') }}" class="text-heart-red text-sm font-semibold hover:underline">Ver todos</a>
                </div>

                @if($recentMessages->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentMessages as $msg)
                            @if($msg->otherProfile)
                                <a href="{{ route('messages.show', $msg->match_id) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-cream transition">
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $msg->otherProfile->foto_principal ? (str_starts_with($msg->otherProfile->foto_principal, 'http') ? $msg->otherProfile->foto_principal : Storage::url($msg->otherProfile->foto_principal)) : 'https://i.pravatar.cc/100' }}"
                                             alt="{{ $msg->otherProfile->nombre }}"
                                             class="w-12 h-12 rounded-full object-cover">
                                        @if($msg->unreadCount > 0)
                                            <span class="absolute -top-1 -right-1 bg-heart-red text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ $msg->unreadCount }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-brown text-sm truncate">{{ $msg->otherProfile->nombre }}</p>
                                        <p class="text-gray-500 text-xs truncate">{{ Str::limit($msg->mensaje, 40) }}</p>
                                    </div>
                                    <span class="text-xs text-gray-400 flex-shrink-0">{{ $msg->created_at->diffForHumans(null, true) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-400 text-sm">Aun no tienes mensajes</p>
                        <a href="{{ route('dashboard') }}" class="text-heart-red text-sm font-semibold hover:underline mt-1 inline-block">Empieza a conocer gente</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== MIS INTERESES ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-brown">Mis Intereses</h2>
                    <a href="{{ route('user.profile.edit') }}" class="text-heart-red text-sm font-semibold hover:underline">Editar</a>
                </div>

                @if($profile->intereses && count($profile->intereses) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->intereses as $interes)
                            <span class="bg-cream text-brown px-4 py-2 rounded-full font-semibold text-sm border border-brown/10">
                                {{ ucfirst($interes) }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm italic">No has anadido intereses todavia.
                        <a href="{{ route('user.profile.edit') }}" class="text-heart-red font-semibold hover:underline">Anadir</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- ===== MIS MATCHES ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-brown">Mis Matches</h2>
                    <a href="{{ route('matches') }}" class="text-heart-red text-sm font-semibold hover:underline">Ver todos ({{ $matchCount }})</a>
                </div>

                @if($matches->count() > 0)
                    <div class="flex gap-3 overflow-x-auto pb-2 -mx-1 px-1">
                        @foreach($matches as $match)
                            @if($match->otherProfile)
                                <a href="{{ route('profile.public', $match->otherProfile->user_id) }}" class="flex-shrink-0 text-center group">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-2 border-heart-red/20 group-hover:border-heart-red transition shadow-sm">
                                        <img src="{{ $match->otherProfile->foto_principal ? (str_starts_with($match->otherProfile->foto_principal, 'http') ? $match->otherProfile->foto_principal : Storage::url($match->otherProfile->foto_principal)) : 'https://i.pravatar.cc/100' }}"
                                             alt="{{ $match->otherProfile->nombre }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-xs font-semibold text-brown mt-1 truncate w-16 sm:w-20">{{ $match->otherProfile->nombre }}</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-gray-400 text-sm">Aun no tienes matches</p>
                        <a href="{{ route('dashboard') }}" class="text-heart-red text-sm font-semibold hover:underline mt-1 inline-block">Descubrir personas</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== MI SUSCRIPCION ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-black text-brown">Mi Suscripcion</h2>
                    <a href="{{ route('subscriptions.dashboard') }}" class="text-heart-red text-sm font-semibold hover:underline">Gestionar</a>
                </div>

                @if($subscription && $plan)
                    <div class="bg-gradient-to-r from-cream to-cream-dark rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-brown text-base">{{ $plan->nombre }}</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Activa</span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>Tipo: <span class="font-medium text-brown">{{ ucfirst($subscription->tipo) }}</span></p>
                            <p>Expira: <span class="font-medium text-brown">{{ $subscription->fecha_expiracion->format('d/m/Y') }}</span></p>
                            @if($subscription->dias_restantes > 0)
                                <p class="text-xs text-gray-500">{{ $subscription->dias_restantes }} dias restantes</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm mb-3">No tienes una suscripcion activa</p>
                        <a href="{{ route('subscriptions.index') }}" class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:shadow-lg transition">
                            Ver Planes
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== ACCIONES RAPIDAS ===== --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('likes.who') }}" class="bg-white rounded-2xl shadow-smooth p-4 flex items-center gap-3 hover:shadow-md transition group">
                <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-pink-200 transition">
                    <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-brown text-sm">Quien me gusta</p>
                    <p class="text-gray-400 text-xs">{{ $likesReceived }} likes</p>
                </div>
            </a>
            <a href="{{ route('likes.my') }}" class="bg-white rounded-2xl shadow-smooth p-4 flex items-center gap-3 hover:shadow-md transition group">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-red-200 transition">
                    <svg class="w-5 h-5 text-heart-red" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-brown text-sm">Mis Likes</p>
                    <p class="text-gray-400 text-xs">Perfiles que te gustan</p>
                </div>
            </a>
            <a href="{{ route('notifications.index') }}" class="bg-white rounded-2xl shadow-smooth p-4 flex items-center gap-3 hover:shadow-md transition group">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-brown text-sm">Notificaciones</p>
                    <p class="text-gray-400 text-xs">Actividad reciente</p>
                </div>
            </a>
            <a href="{{ route('blocked.index') }}" class="bg-white rounded-2xl shadow-smooth p-4 flex items-center gap-3 hover:shadow-md transition group">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-gray-200 transition">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-brown text-sm">Bloqueados</p>
                    <p class="text-gray-400 text-xs">Usuarios bloqueados</p>
                </div>
            </a>
        </div>

        {{-- ===== CERRAR SESION ===== --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full bg-white rounded-2xl shadow-smooth p-4 flex items-center justify-center gap-2 text-gray-500 hover:text-heart-red hover:shadow-md transition font-semibold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Cerrar Sesion
            </button>
        </form>
    </div>

    {{-- ===== BARRA DE NAVEGACION INFERIOR ===== --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 shadow-lg">
        <div class="max-w-4xl mx-auto flex items-center justify-around py-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('dashboard') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] font-semibold">Inicio</span>
            </a>
            <a href="{{ route('dashboard') }}?buscar=1" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition text-gray-400 hover:text-brown">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-[10px] font-semibold">Buscar</span>
            </a>
            <a href="{{ route('messages') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('messages*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
                <div class="relative">
                    <svg class="w-6 h-6" fill="{{ request()->routeIs('messages*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold">Mensajes</span>
            </a>
            <a href="{{ route('subscriptions.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('subscriptions.index') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('subscriptions.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="text-[10px] font-semibold">Planes</span>
            </a>
            <a href="{{ route('user.profile.show') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('user.profile.show') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('user.profile.show') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-semibold">Mi perfil</span>
            </a>
        </div>
        {{-- Safe area para moviles con notch --}}
        <div class="h-[env(safe-area-inset-bottom)]"></div>
    </nav>
</div>

{{-- ===== MODAL FULLSCREEN PARA FOTOS ===== --}}
<script>
    const allPhotoSrcs = @json(array_values(array_map(function($photo) {
        return str_starts_with($photo, 'http') ? $photo : Storage::url($photo);
    }, $allPhotos)));

    let currentFsIndex = 0;

    function openFullscreen(index) {
        currentFsIndex = index;
        const modal = document.createElement('div');
        modal.id = 'fullscreen-modal';
        modal.className = 'fixed inset-0 bg-black/95 z-[9999] flex items-center justify-center';
        modal.innerHTML = `
            <button onclick="closeFullscreen()" class="absolute top-4 right-4 z-50 text-white/80 hover:text-white transition p-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            ${allPhotoSrcs.length > 1 ? `
                <button onclick="fsNav(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 z-50 text-white/70 hover:text-white transition bg-white/10 hover:bg-white/20 rounded-full p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="fsNav(1)" class="absolute right-3 top-1/2 -translate-y-1/2 z-50 text-white/70 hover:text-white transition bg-white/10 hover:bg-white/20 rounded-full p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div class="absolute bottom-6 left-0 right-0 z-50 flex justify-center gap-1.5" id="fs-dots">
                    ${allPhotoSrcs.map((_, i) => `<div class="w-2 h-2 rounded-full transition-all ${i === index ? 'bg-white w-6' : 'bg-white/40'}"></div>`).join('')}
                </div>
            ` : ''}
            <img id="fs-image" src="${allPhotoSrcs[index]}" class="max-w-full max-h-full object-contain p-4" alt="Foto">
        `;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeFullscreen();
        });

        document.addEventListener('keydown', fsKeyHandler);
    }

    function closeFullscreen() {
        const modal = document.getElementById('fullscreen-modal');
        if (modal) { modal.remove(); document.body.style.overflow = ''; }
        document.removeEventListener('keydown', fsKeyHandler);
    }

    function fsNav(dir) {
        currentFsIndex = (currentFsIndex + dir + allPhotoSrcs.length) % allPhotoSrcs.length;
        document.getElementById('fs-image').src = allPhotoSrcs[currentFsIndex];
        const dots = document.querySelectorAll('#fs-dots > div');
        dots.forEach((d, i) => {
            d.className = i === currentFsIndex ? 'w-6 h-2 rounded-full transition-all bg-white' : 'w-2 h-2 rounded-full transition-all bg-white/40';
        });
    }

    function fsKeyHandler(e) {
        if (e.key === 'Escape') closeFullscreen();
        else if (e.key === 'ArrowLeft') fsNav(-1);
        else if (e.key === 'ArrowRight') fsNav(1);
    }
</script>
@endsection
