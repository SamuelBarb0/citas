@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky moderno -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Notificaciones</h1>
                    <p class="text-gray-500 text-sm">Mantente al día con tus matches y mensajes</p>
                </div>
                @if($notifications->where('read_at', null)->count() > 0)
                    <form action="{{ route('notifications.mark-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-heart-red hover:text-heart-red-light font-semibold transition">
                            Marcar todas como leídas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Lista de Notificaciones -->
            @if($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                        @endphp

                        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden hover:shadow-lg transition {{ $isUnread ? 'border-l-4 border-heart-red' : '' }}">
                            <div class="p-5 flex items-start gap-4">
                                <!-- Icono/Foto -->
                                <div class="flex-shrink-0">
                                    @if($data['type'] === 'new_match')
                                        @if(isset($data['matched_user_photo']) && $data['matched_user_photo'])
                                            <img src="{{ $data['matched_user_photo'] }}"
                                                 alt="{{ $data['matched_user_name'] }}"
                                                 class="w-14 h-14 rounded-full object-cover border-2 border-heart-red">
                                        @else
                                            <div class="w-14 h-14 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center">
                                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    @elseif($data['type'] === 'new_message')
                                        @if(isset($data['sender_photo']) && $data['sender_photo'])
                                            <img src="{{ $data['sender_photo'] }}"
                                                 alt="{{ $data['sender_name'] }}"
                                                 class="w-14 h-14 rounded-full object-cover border-2 border-blue-500">
                                        @else
                                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- Contenido -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-brown font-black text-base mb-1">{{ $data['message'] }}</p>

                                    @if($data['type'] === 'new_message' && isset($data['message_preview']))
                                        <p class="text-gray-600 text-sm mb-2 truncate">{{ $data['message_preview'] }}</p>
                                    @endif

                                    <p class="text-xs text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Acciones -->
                                <div class="flex flex-col items-end gap-2">
                                    @if($isUnread)
                                        <span class="w-3 h-3 bg-heart-red rounded-full"></span>
                                    @endif

                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm text-heart-red hover:text-heart-red-light font-semibold transition">
                                            Ver
                                        </button>
                                    </form>

                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                @if($notifications->hasPages())
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <!-- Estado vacío -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-cream to-cream-dark rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-brown/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-brown mb-4">
                            No tienes notificaciones
                        </h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Cuando recibas un nuevo match o mensaje, te lo notificaremos aquí.
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-10 py-4 rounded-full font-black text-lg hover:shadow-glow transition shadow-xl">
                            Descubrir Perfiles
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
