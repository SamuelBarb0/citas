@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-brown mb-2">🔔 Notificaciones</h1>
                <p class="text-gray-600">Mantente al día con tus matches y mensajes</p>
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
                                        <div class="w-14 h-14 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center text-white text-2xl">
                                            💕
                                        </div>
                                    @endif
                                @elseif($data['type'] === 'new_message')
                                    @if(isset($data['sender_photo']) && $data['sender_photo'])
                                        <img src="{{ $data['sender_photo'] }}"
                                             alt="{{ $data['sender_name'] }}"
                                             class="w-14 h-14 rounded-full object-cover border-2 border-blue-500">
                                    @else
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl">
                                            💬
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Contenido -->
                            <div class="flex-1 min-w-0">
                                <p class="text-brown font-semibold mb-1">{{ $data['message'] }}</p>

                                @if($data['type'] === 'new_message' && isset($data['message_preview']))
                                    <p class="text-gray-600 text-sm mb-2 truncate">{{ $data['message_preview'] }}</p>
                                @endif

                                <p class="text-xs text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex flex-col gap-2">
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
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Estado vacío -->
            <div class="bg-white rounded-3xl shadow-smooth p-12 text-center">
                <div class="text-8xl mb-6">🔔</div>
                <h3 class="text-2xl font-bold text-brown mb-3">
                    No tienes notificaciones
                </h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Cuando recibas un nuevo match o mensaje, te lo notificaremos aquí.
                </p>
                <a href="{{ route('dashboard') }}" class="inline-block bg-heart-red text-white px-8 py-4 rounded-full font-bold hover:opacity-90 transition shadow-lg">
                    🔥 Descubrir Perfiles
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
