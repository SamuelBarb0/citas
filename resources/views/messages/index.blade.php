@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky moderno -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Mensajes</h1>
                    <p class="text-gray-500 text-sm">Tus conversaciones activas</p>
                </div>
                <a href="{{ route('matches') }}" class="bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-2 rounded-full hover:shadow-glow transition font-semibold text-sm">
                    Ver Matches
                </a>
            </div>
        </div>
    </div>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Lista de conversaciones -->
            @if($conversations->count() > 0)
                <div class="space-y-3">
                    @foreach($conversations as $conversation)
                        @if($conversation['profile'])
                            <a
                                href="{{ route('messages.show', $conversation['match_id']) }}"
                                class="block bg-white rounded-2xl shadow-smooth hover:shadow-lg transition-all hover:-translate-y-1"
                            >
                                <div class="flex items-center gap-4 p-5">
                                    <!-- Avatar -->
                                    <div class="relative flex-shrink-0">
                                        <img
                                            src="{{ $conversation['profile']->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($conversation['user']->name) . '&size=200&background=A67C52&color=fff' }}"
                                            alt="{{ $conversation['user']->name }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-gray-100"
                                        >
                                        @if($conversation['unread_count'] > 0)
                                            <div class="absolute -top-1 -right-1 bg-heart-red text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                                {{ $conversation['unread_count'] }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Información -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h3 class="font-black text-brown text-lg">
                                                {{ $conversation['profile']->nombre }}
                                            </h3>
                                            @if($conversation['last_message'])
                                                <span class="text-xs text-gray-500">
                                                    {{ $conversation['last_message']->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($conversation['last_message'])
                                            <p class="text-sm text-gray-600 truncate {{ $conversation['unread_count'] > 0 ? 'font-semibold' : '' }}">
                                                @if($conversation['last_message']->sender_id == auth()->id())
                                                    <span class="text-gray-400">Tú:</span>
                                                @endif
                                                {{ $conversation['last_message']->mensaje }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500">
                                                ¡Hicisteis match! Envía el primer mensaje
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Indicador visual -->
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            @else
                <!-- Estado vacío -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-cream to-cream-dark rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-brown/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-brown mb-4">
                            No tienes conversaciones aún
                        </h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Cuando hagas match con alguien, podrás empezar a chatear aquí.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-10 py-4 rounded-full font-black text-lg hover:shadow-glow transition shadow-xl"
                            >
                                Descubrir Perfiles
                            </a>
                            <a
                                href="{{ route('matches') }}"
                                class="inline-block bg-brown text-white px-10 py-4 rounded-full font-black text-lg hover:opacity-90 transition shadow-xl"
                            >
                                Ver Matches
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
