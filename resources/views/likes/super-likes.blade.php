<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
        <!-- Header -->
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="font-bold text-brown text-lg flex items-center gap-2">
                                <span>⭐</span> Super Likes Recibidos
                            </h1>
                            <p class="text-xs text-gray-500">{{ count($superLikes) }} super like(s)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                @if(count($superLikes) > 0)
                    <!-- Info destacada -->
                    <div class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-2xl p-6">
                        <div class="flex gap-3">
                            <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-black text-yellow-900 text-lg mb-1">¡Tienes admiradores especiales!</h3>
                                <p class="text-yellow-700 text-sm">Estas personas te dieron un Super Like. Les gustas mucho más de lo normal.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de super likes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($superLikes as $like)
                            @php
                                $profile = $like->user->profile;
                            @endphp
                            <div class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all hover:-translate-y-2 relative">
                                <!-- Badge de Super Like -->
                                <div class="absolute top-4 right-4 z-10 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 animate-pulse">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-bold text-sm">SUPER LIKE</span>
                                </div>

                                <!-- Foto -->
                                <a href="{{ route('profile.public', $like->user->id) }}" class="block">
                                    @if($profile && $profile->foto_principal)
                                        <img src="{{ $profile->foto_principal }}"
                                             alt="{{ $profile->nombre }}"
                                             class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-72 bg-gradient-to-br from-brown to-heart-red flex items-center justify-center">
                                            <svg class="w-24 h-24 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <!-- Info -->
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-black text-brown text-xl flex items-center gap-2">
                                                {{ $profile->nombre ?? $like->user->name }}
                                                @if($profile && $profile->verified)
                                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </h3>
                                            @if($profile)
                                                <p class="text-gray-600 text-sm">{{ $profile->edad }} años • {{ $profile->ciudad }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($profile && $profile->biografia)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $profile->biografia }}</p>
                                    @endif

                                    <p class="text-xs text-gray-400 mb-4">
                                        Super Like recibido {{ $like->created_at->diffForHumans() }}
                                    </p>

                                    <!-- Botón de acción -->
                                    <a href="{{ route('profile.public', $like->user->id) }}"
                                       class="block w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-3 px-6 rounded-xl font-bold text-center hover:shadow-glow transition">
                                        Ver Perfil Completo
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="text-center py-16">
                        <div class="w-32 h-32 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-16 h-16 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-brown mb-2">Aún no tienes Super Likes</h3>
                        <p class="text-gray-500 mb-6 max-w-md mx-auto">Cuando alguien te dé un Super Like, significando que le gustas mucho, aparecerá aquí.</p>
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-heart-red to-heart-red-light text-white rounded-full font-bold hover:shadow-glow transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Descubrir Personas
                        </a>
                    </div>
                @endif

                <!-- Info card -->
                <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-2xl p-6">
                    <div class="flex gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-yellow-900 mb-2">¿Qué son los Super Likes?</h4>
                            <p class="text-sm text-yellow-700 mb-2">Un Super Like es una forma especial de demostrar interés. Significa que le gustas mucho más que un like normal.</p>
                            <p class="text-xs text-yellow-600">💡 Consejo: Responde a tus Super Likes - ¡estas personas realmente están interesadas en ti!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
