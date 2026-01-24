@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header sticky -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brown">Quien te ha dado like</h1>
                    <p class="text-gray-500 text-sm">Funcion Premium</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition font-semibold text-sm">
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Tarjeta principal de upgrade -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-pink-500 via-heart-red to-pink-600 p-8 text-center text-white relative overflow-hidden">
                    <!-- Decoracion de fondo -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-4 left-8 text-6xl">💖</div>
                        <div class="absolute top-12 right-12 text-4xl">💕</div>
                        <div class="absolute bottom-8 left-16 text-3xl">❤️</div>
                        <div class="absolute bottom-4 right-8 text-5xl">💗</div>
                    </div>

                    <div class="relative z-10">
                        <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-5xl">🔒</span>
                        </div>

                        @if($likesCount > 0)
                            <div class="inline-block bg-white text-heart-red px-6 py-2 rounded-full font-black text-lg mb-4 shadow-lg">
                                {{ $likesCount }} {{ $likesCount === 1 ? 'persona' : 'personas' }} te {{ $likesCount === 1 ? 'ha' : 'han' }} dado like
                            </div>
                        @endif

                        <h2 class="text-3xl font-black mb-2">¡Descubre quien le gustas!</h2>
                        <p class="text-white/90 text-lg">Con Premium puedes ver quien te ha dado like</p>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-8">
                    <!-- Preview borroso de "perfiles" -->
                    @if($likesCount > 0)
                        <div class="mb-8">
                            <p class="text-center text-gray-500 mb-4 font-semibold">Vista previa de quien le gustas:</p>
                            <div class="grid grid-cols-3 gap-3">
                                @for($i = 0; $i < min($likesCount, 6); $i++)
                                    <div class="aspect-square rounded-2xl bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden">
                                        <!-- Silueta borrosa -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-pink-200 to-red-200 blur-xl opacity-60"></div>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-16 h-16 rounded-full bg-white/50 blur-lg"></div>
                                        </div>
                                        <!-- Icono de candado -->
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-10 h-10 bg-white/80 rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Badge de like -->
                                        <div class="absolute top-2 right-2 bg-heart-red text-white px-2 py-1 rounded-full text-[10px] font-bold">
                                            ❤️
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            @if($likesCount > 6)
                                <p class="text-center text-gray-400 mt-3 text-sm">Y {{ $likesCount - 6 }} mas...</p>
                            @endif
                        </div>
                    @endif

                    <!-- Beneficios de Premium -->
                    <div class="bg-gradient-to-br from-cream to-yellow-50 rounded-2xl p-6 mb-8">
                        <h3 class="font-black text-brown text-lg mb-4 text-center">Con Premium podras:</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-semibold">Ver quien te ha dado like</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-semibold">Hacer match instantaneo</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-semibold">Mensajes ilimitados</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-semibold">Likes ilimitados</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-semibold">Mas fotos en tu perfil</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="text-center">
                        <a href="{{ route('subscriptions.index') }}" class="inline-block bg-gradient-to-r from-pink-500 via-heart-red to-pink-600 text-white px-12 py-5 rounded-full font-black text-xl hover:shadow-glow transition shadow-xl">
                            Ver Planes Premium
                        </a>
                        <p class="text-gray-400 mt-4 text-sm">Cancela cuando quieras</p>
                    </div>
                </div>
            </div>

            <!-- Alternativa: seguir descubriendo -->
            <div class="mt-8 text-center">
                <p class="text-gray-500 mb-4">¿Prefieres seguir descubriendo perfiles?</p>
                <a href="{{ route('dashboard') }}" class="inline-block bg-white text-brown border-2 border-brown px-8 py-3 rounded-full font-bold hover:bg-brown hover:text-white transition">
                    Volver al Descubrimiento
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
