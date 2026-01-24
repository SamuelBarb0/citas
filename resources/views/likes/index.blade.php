@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky moderno -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Mis Likes</h1>
                    <p class="text-gray-500 text-sm">{{ $likes->total() }} {{ $likes->total() === 1 ? 'persona te gusta' : 'personas te gustan' }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-2 rounded-full hover:shadow-glow transition font-semibold text-sm">
                    Descubrir mas
                </a>
            </div>
        </div>
    </div>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @if($likes->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($likes as $like)
                        @php
                            $user = $like->likedUser;
                            $profile = $user->profile;
                        @endphp
                        @if($profile)
                            <div class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                                <!-- Imagen con efecto hover -->
                                <a href="{{ route('profile.public', $user->id) }}" class="block relative">
                                    <div class="relative overflow-hidden">
                                        @php
                                            $foto = $profile->foto_principal;
                                            if ($foto && !str_starts_with($foto, 'http')) {
                                                $foto = Storage::url($foto);
                                            } elseif (!$foto) {
                                                $foto = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=400&background=A67C52&color=fff';
                                            }
                                        @endphp
                                        <img
                                            src="{{ $foto }}"
                                            alt="{{ $profile->nombre }}"
                                            class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500"
                                        >
                                        <!-- Gradiente overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                        <!-- Badge de like enviado -->
                                        <div class="absolute top-4 right-4 bg-gradient-to-r from-heart-red to-pink-500 text-white px-4 py-2 rounded-full text-xs font-black shadow-lg">
                                            Te gusta
                                        </div>

                                        <!-- Nombre y edad sobre la imagen -->
                                        <div class="absolute bottom-4 left-4 right-4 text-white">
                                            <h3 class="text-2xl font-black mb-1">
                                                {{ $profile->nombre }}, {{ $profile->edad }}
                                            </h3>
                                            <div class="flex items-center gap-1 text-sm">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>{{ $profile->ciudad }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Informacion y acciones -->
                                <div class="p-5">
                                    <!-- Cuando diste like -->
                                    <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-4 mb-4 border-2 border-red-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-2xl">❤️</span>
                                            <p class="text-sm text-red-700 font-bold">
                                                Like dado {{ $like->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Botones de accion -->
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <a
                                            href="{{ route('profile.public', $user->id) }}"
                                            class="bg-gradient-to-r from-brown to-brown-dark text-white py-3 rounded-xl font-bold text-center hover:shadow-lg transition flex items-center justify-center gap-2 text-sm"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Ver Perfil
                                        </a>
                                        <form action="{{ route('like.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-white text-gray-500 border-2 border-gray-300 py-3 rounded-xl font-bold text-center hover:bg-gray-100 hover:border-gray-400 transition flex items-center justify-center gap-2 text-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Quitar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Paginacion -->
                @if($likes->hasPages())
                    <div class="mt-8">
                        {{ $likes->links() }}
                    </div>
                @endif

                <!-- CTA al final -->
                <div class="mt-12 bg-gradient-to-r from-heart-red to-heart-red-light rounded-3xl p-8 text-center text-white shadow-2xl">
                    <div class="max-w-2xl mx-auto">
                        <div class="text-5xl mb-4">💕</div>
                        <h3 class="text-3xl font-black mb-3">¿Buscas mas conexiones?</h3>
                        <p class="text-white/90 mb-6 text-lg">
                            Sigue descubriendo perfiles increibles
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-block bg-white text-heart-red px-10 py-4 rounded-full font-black text-lg hover:shadow-glow transition">
                            Seguir Descubriendo
                        </a>
                    </div>
                </div>

            @else
                <!-- Estado vacio -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-2xl p-12 text-center">
                        <div class="w-40 h-40 mx-auto mb-6 bg-gradient-to-br from-red-100 to-pink-100 rounded-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>

                        <h3 class="text-3xl font-black text-brown mb-4">
                            Todavia no has dado likes
                        </h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Empieza a explorar perfiles y da like<br>
                            a las personas que te interesen.
                        </p>

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
@endsection
