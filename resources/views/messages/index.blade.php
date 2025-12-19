@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-brown mb-2">💬 Mensajes</h1>
            <p class="text-gray-600">Tus conversaciones con los matches</p>
        </div>

        <!-- Lista de conversaciones -->
        @if($conversations->count() > 0)
            <div class="bg-white rounded-3xl shadow-smooth overflow-hidden">
                @foreach($conversations as $conversation)
                    @if($conversation['profile'])
                        <a
                            href="{{ route('messages.show', $conversation['match_id']) }}"
                            class="flex items-center gap-4 p-5 border-b border-gray-100 hover:bg-cream transition-colors"
                        >
                            <!-- Avatar -->
                            <div class="relative flex-shrink-0">
                                <img
                                    src="{{ $conversation['profile']->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($conversation['user']->name) . '&size=200&background=A67C52&color=fff' }}"
                                    alt="{{ $conversation['user']->name }}"
                                    class="w-16 h-16 rounded-full object-cover"
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
                                    <h3 class="font-bold text-brown text-lg">
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
                                            Tú:
                                        @endif
                                        {{ $conversation['last_message']->mensaje }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500 italic">
                                        ¡Hicisteis match! Envía el primer mensaje 👋
                                    </p>
                                @endif
                            </div>

                            <!-- Indicador visual -->
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white rounded-3xl shadow-smooth p-12 text-center">
                <div class="text-8xl mb-6">📭</div>
                <h3 class="text-2xl font-bold text-brown mb-3">
                    No tienes conversaciones aún
                </h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Cuando hagas match con alguien, podrás empezar a chatear aquí.
                </p>
                <div class="flex gap-4 justify-center">
                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-block bg-heart-red text-white px-8 py-4 rounded-full font-bold hover:opacity-90 transition shadow-lg"
                    >
                        🔥 Descubrir Perfiles
                    </a>
                    <a
                        href="{{ route('matches') }}"
                        class="inline-block bg-brown text-white px-8 py-4 rounded-full font-bold hover:opacity-90 transition shadow-lg"
                    >
                        💕 Ver Matches
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
