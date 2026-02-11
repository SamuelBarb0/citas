@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky moderno -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Usuarios Bloqueados</h1>
                    <p class="text-gray-500 text-sm">{{ count($blockedUsers) }} {{ count($blockedUsers) === 1 ? 'usuario' : 'usuarios' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-6 py-4 rounded-2xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(count($blockedUsers) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($blockedUsers as $blocked)
                        @php
                            $profile = $blocked->blockedUser->profile;
                        @endphp
                        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden hover:shadow-lg transition-all">
                            <div class="flex gap-4 p-4">
                                <!-- Foto -->
                                <div class="flex-shrink-0">
                                    @if($profile && $profile->foto_principal)
                                        <img src="{{ $profile->foto_principal }}"
                                             alt="{{ $profile->nombre }}"
                                             class="w-20 h-20 rounded-xl object-cover">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-br from-brown to-heart-red rounded-xl flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-black text-brown text-lg truncate">
                                        {{ $profile->nombre ?? $blocked->blockedUser->name }}
                                    </h3>
                                    @if($profile)
                                        <p class="text-sm text-gray-500">{{ $profile->edad }} años • {{ $profile->ciudad }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2">
                                        Bloqueado {{ $blocked->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Botón desbloquear -->
                                <div class="flex-shrink-0">
                                    <form action="{{ route('block.destroy', $blocked->blocked_user_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-sm font-semibold hover:bg-red-100 transition">
                                            Desbloquear
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($blocked->reason)
                                <div class="px-4 pb-4">
                                    <div class="bg-gray-50 rounded-xl p-3">
                                        <p class="text-xs text-gray-600"><strong>Razón:</strong> {{ $blocked->reason }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado vacío -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-brown mb-4">No has bloqueado a nadie</h3>
                        <p class="text-gray-600 mb-8 text-lg">Cuando bloquees a alguien, aparecerá aquí.</p>
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-10 py-4 bg-gradient-to-r from-heart-red to-heart-red-light text-white rounded-full font-black text-lg hover:shadow-glow transition shadow-xl">
                            Descubrir Personas
                        </a>
                    </div>
                </div>
            @endif

            <!-- Info card -->
            <div class="mt-8 bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
                <div class="flex gap-3">
                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-blue-900 mb-2">Sobre los bloqueos</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Los usuarios bloqueados no pueden ver tu perfil</li>
                            <li>• No aparecerán en tu descubrimiento</li>
                            <li>• Si tenían un match, se eliminó automáticamente</li>
                            <li>• Puedes desbloquearlos en cualquier momento</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
