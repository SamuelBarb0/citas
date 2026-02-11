@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky moderno -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Mis Matches</h1>
                    <p class="text-gray-500 text-sm">{{ $matchesData->count() }} {{ $matchesData->count() === 1 ? 'persona' : 'personas' }} compatibles</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-2 rounded-full hover:shadow-glow transition font-semibold text-sm">
                    Descubrir más
                </a>
            </div>
        </div>
    </div>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Mensajes de feedback flotantes -->
            @if(session('success'))
                <div class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-slide-in">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-slide-in">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Grid de Matches modernizado -->
            @if($matchesData->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($matchesData as $match)
                        @if($match['profile'])
                            <div class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                                <!-- Imagen con efecto hover -->
                                <a href="{{ route('profile.public', $match['user']->id) }}" class="block relative">
                                    <div class="relative overflow-hidden">
                                        <img
                                            src="{{ $match['profile']->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($match['user']->name) . '&size=400&background=A67C52&color=fff' }}"
                                            alt="{{ $match['user']->name }}"
                                            class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500"
                                        >
                                        <!-- Gradiente overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                        <!-- Badge de match animado -->
                                        <div class="absolute top-4 right-4 bg-gradient-to-r from-heart-red to-heart-red-light text-white px-4 py-2 rounded-full text-xs font-black shadow-lg animate-pulse">
                                            MATCH
                                        </div>

                                        <!-- Nombre y edad sobre la imagen -->
                                        <div class="absolute bottom-4 left-4 right-4 text-white">
                                            <h3 class="text-2xl font-black mb-1">
                                                {{ $match['profile']->nombre }}, {{ $match['profile']->edad }}
                                            </h3>
                                            <div class="flex items-center gap-1 text-sm">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>{{ $match['profile']->ciudad }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Información y acciones -->
                                <div class="p-5">
                                    <!-- Último mensaje preview -->
                                    @if($match['last_message'])
                                        <div class="bg-gradient-to-r from-cream to-yellow-50 rounded-2xl p-4 mb-4 border-2 border-yellow-100">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-brown flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-xs text-gray-500 font-semibold mb-1">
                                                        {{ $match['last_message']->created_at->diffForHumans() }}
                                                    </p>
                                                    <p class="text-sm text-brown font-medium truncate">
                                                        {{ Str::limit($match['last_message']->mensaje, 40) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-gradient-to-r from-cream to-cream-dark rounded-2xl p-4 mb-4 border-2 border-brown/20">
                                            <div class="flex items-center justify-center gap-2">
                                                <p class="text-sm text-brown font-bold">
                                                    ¡Rompe el hielo!
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Botones de acción -->
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <a
                                            href="{{ route('messages.show', $match['match_id']) }}"
                                            class="bg-gradient-to-r from-heart-red to-heart-red-light text-white py-3 rounded-xl font-bold text-center hover:shadow-glow transition flex items-center justify-center gap-2 text-sm"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            Chatear
                                        </a>
                                        <a
                                            href="{{ route('profile.public', $match['user']->id) }}"
                                            class="bg-white text-brown border-2 border-brown py-3 rounded-xl font-bold text-center hover:bg-brown hover:text-white transition flex items-center justify-center gap-2 text-sm"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Perfil
                                        </a>
                                    </div>

                                    <!-- Opción de unmatch -->
                                    <form action="{{ route('matches.destroy', $match['match_id']) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-xs text-gray-400 hover:text-red-500 transition font-semibold py-2 flex items-center justify-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Eliminar match
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- CTA al final para seguir explorando -->
                <div class="mt-12 bg-gradient-to-r from-heart-red to-heart-red-light rounded-3xl p-8 text-center text-white shadow-2xl">
                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-3xl font-black mb-3">¿Quieres más matches?</h3>
                        <p class="text-white/90 mb-6 text-lg">
                            Sigue descubriendo perfiles increíbles en Mallorca
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-white text-heart-red px-10 py-4 rounded-full font-black text-lg hover:shadow-glow transition">
                            Seguir Descubriendo
                        </a>
                    </div>
                </div>

            @else
                <!-- Estado vacío modernizado -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                        <!-- Ilustración vacío -->
                        <div class="w-40 h-40 mx-auto mb-6 bg-gradient-to-br from-cream to-cream-dark rounded-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-brown/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>

                        <h3 class="text-3xl font-black text-brown mb-4">
                            Todavía no tienes matches
                        </h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Empieza a dar likes a perfiles que te interesen.<br>
                            Cuando alguien también te de like, ¡harás match! 💕
                        </p>

                        <!-- Pasos para conseguir matches -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-cream rounded-2xl p-6">
                                <h4 class="font-bold text-brown mb-2">1. Explora</h4>
                                <p class="text-sm text-gray-600">Descubre perfiles interesantes</p>
                            </div>
                            <div class="bg-cream rounded-2xl p-6">
                                <h4 class="font-bold text-brown mb-2">2. Da Like</h4>
                                <p class="text-sm text-gray-600">Muestra tu interés</p>
                            </div>
                            <div class="bg-cream rounded-2xl p-6">
                                <h4 class="font-bold text-brown mb-2">3. ¡Match!</h4>
                                <p class="text-sm text-gray-600">Empieza a chatear</p>
                            </div>
                        </div>

                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-12 py-5 rounded-full font-black text-lg hover:shadow-glow transition shadow-xl"
                        >
                            Empezar a Descubrir
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>
@endsection
