@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky moderno -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Quien te ha dado like</h1>
                    <p class="text-gray-500 text-sm">{{ $likesCount }} {{ $likesCount === 1 ? 'persona le gustas' : 'personas les gustas' }}</p>
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
                            $user = $like->user;
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

                                        <!-- Badge de like -->
                                        <div class="absolute top-4 right-4 bg-gradient-to-r from-pink-500 to-heart-red text-white px-4 py-2 rounded-full text-xs font-black shadow-lg">
                                            Le gustas
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
                                    <!-- Cuando le dio like -->
                                    <div class="bg-gradient-to-r from-pink-50 to-red-50 rounded-2xl p-4 mb-4 border-2 border-pink-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <p class="text-sm text-pink-700 font-bold">
                                                Le gustas desde {{ $like->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Botones de accion -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <form action="{{ route('like.store', $user->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="liked_user_id" value="{{ $user->id }}">
                                            <button type="submit" class="w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-3 rounded-xl font-bold text-center hover:shadow-glow transition flex items-center justify-center gap-2 text-sm">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                </svg>
                                                Like
                                            </button>
                                        </form>
                                        <a
                                            href="{{ route('profile.public', $user->id) }}"
                                            class="bg-white text-brown border-2 border-brown py-3 rounded-xl font-bold text-center hover:bg-brown hover:text-white transition flex items-center justify-center gap-2 text-sm"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Perfil
                                        </a>
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
                <div class="mt-12 bg-gradient-to-r from-pink-500 to-heart-red rounded-3xl p-8 text-center text-white shadow-2xl">
                    <div class="max-w-2xl mx-auto">
                        <h3 class="text-3xl font-black mb-3">¡Les gustas!</h3>
                        <p class="text-white/90 mb-6 text-lg">
                            Da like de vuelta para hacer match y empezar a chatear
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
                        <div class="w-40 h-40 mx-auto mb-6 bg-gradient-to-br from-pink-100 to-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>

                        <h3 class="text-3xl font-black text-brown mb-4">
                            Todavia nadie te ha dado like
                        </h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            ¡No te preocupes! Mejora tu perfil con fotos atractivas<br>
                            y una biografia interesante para recibir mas likes.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a
                                href="{{ route('user.profile.edit') }}"
                                class="inline-block bg-brown text-white px-8 py-4 rounded-full font-black hover:bg-brown-dark transition"
                            >
                                Mejorar mi perfil
                            </a>
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-8 py-4 rounded-full font-black hover:shadow-glow transition"
                            >
                                Descubrir perfiles
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
