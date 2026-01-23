<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
        <!-- Header compacto y moderno -->
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h1 class="font-bold text-brown text-lg">Descubre</h1>
                            <p class="text-xs text-gray-500"><span id="profiles-count">{{ count($perfiles) }}</span> perfiles</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="filters-btn" class="bg-white text-brown px-4 py-2 rounded-full hover:bg-cream transition font-semibold text-sm border-2 border-brown/20 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filtros
                        </button>
                        <a href="{{ route('user.profile.show') }}" class="bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-2 rounded-full hover:shadow-glow transition font-semibold text-sm">
                            Mi Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de filtros deslizante -->
        <div id="filters-panel" class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
            <div class="p-6">
                <!-- Header del panel -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black text-brown">Filtros de Búsqueda</h2>
                    <button id="close-filters" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form id="filters-form" method="GET" action="{{ route('dashboard') }}">
                    <!-- Filtro de Edad -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-brown mb-3">Rango de Edad</label>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <input type="number" name="edad_min" value="{{ request('edad_min', 18) }}" min="18" max="99"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none">
                                <p class="text-xs text-gray-500 mt-1">Mínimo</p>
                            </div>
                            <span class="text-gray-400">-</span>
                            <div class="flex-1">
                                <input type="number" name="edad_max" value="{{ request('edad_max', 99) }}" min="18" max="99"
                                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none">
                                <p class="text-xs text-gray-500 mt-1">Máximo</p>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro de Ciudad -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-brown mb-3">Ciudad</label>
                        <input type="text" name="ciudad" value="{{ request('ciudad') }}" placeholder="Ej: Palma de Mallorca"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none">
                    </div>

                    <!-- Filtro de Género -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-brown mb-3">Mostrar personas de género</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="busco" value="" {{ request('busco') == '' ? 'checked' : '' }} class="peer sr-only">
                                <div class="px-4 py-3 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm peer-checked:border-heart-red peer-checked:bg-heart-red peer-checked:text-white transition">
                                    Todos
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="busco" value="hombre" {{ request('busco') == 'hombre' ? 'checked' : '' }} class="peer sr-only">
                                <div class="px-4 py-3 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm peer-checked:border-heart-red peer-checked:bg-heart-red peer-checked:text-white transition">
                                    Hombres
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="busco" value="mujer" {{ request('busco') == 'mujer' ? 'checked' : '' }} class="peer sr-only">
                                <div class="px-4 py-3 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm peer-checked:border-heart-red peer-checked:bg-heart-red peer-checked:text-white transition">
                                    Mujeres
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="busco" value="no-binario" {{ request('busco') == 'no-binario' ? 'checked' : '' }} class="peer sr-only">
                                <div class="px-4 py-3 rounded-xl border-2 border-gray-200 text-center font-semibold text-sm peer-checked:border-heart-red peer-checked:bg-heart-red peer-checked:text-white transition">
                                    No binario
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Filtro de Orientación Sexual -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-brown mb-3">Orientación Sexual</label>
                        <select name="orientacion_sexual" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none">
                            <option value="">Todas</option>
                            <option value="heterosexual" {{ request('orientacion_sexual') == 'heterosexual' ? 'selected' : '' }}>Heterosexual</option>
                            <option value="gay" {{ request('orientacion_sexual') == 'gay' ? 'selected' : '' }}>Gay</option>
                            <option value="lesbiana" {{ request('orientacion_sexual') == 'lesbiana' ? 'selected' : '' }}>Lesbiana</option>
                            <option value="bisexual" {{ request('orientacion_sexual') == 'bisexual' ? 'selected' : '' }}>Bisexual</option>
                            <option value="pansexual" {{ request('orientacion_sexual') == 'pansexual' ? 'selected' : '' }}>Pansexual</option>
                            <option value="asexual" {{ request('orientacion_sexual') == 'asexual' ? 'selected' : '' }}>Asexual</option>
                            <option value="queer" {{ request('orientacion_sexual') == 'queer' ? 'selected' : '' }}>Queer</option>
                            <option value="otra" {{ request('orientacion_sexual') == 'otra' ? 'selected' : '' }}>Otra</option>
                        </select>
                    </div>

                    <!-- Filtro de Intereses -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-brown mb-3">Intereses</label>
                        <input type="text" name="intereses" value="{{ request('intereses') }}" placeholder="Ej: Deportes, Música, Viajes"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Separa con comas</p>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3 mt-8">
                        <button type="button" id="clear-filters" class="flex-1 px-6 py-4 bg-gray-100 text-brown rounded-xl font-bold hover:bg-gray-200 transition">
                            Limpiar
                        </button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-heart-red to-heart-red-light text-white rounded-xl font-bold hover:shadow-glow transition">
                            Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Overlay oscuro para el panel de filtros -->
        <div id="filters-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 opacity-0 pointer-events-none transition-opacity duration-300"></div>

        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-lg mx-auto">
                @if(isset($searchExpanded) && $searchExpanded)
                    <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                        <p class="text-blue-700 text-sm">
                            <span class="font-semibold">Ampliamos tu búsqueda</span> para mostrarte más personas.
                            <a href="{{ route('user.profile.edit') }}" class="underline hover:text-blue-900">Actualiza tus preferencias</a>
                        </p>
                    </div>
                @endif

                @if(count($perfiles) > 0)
                    <!-- Contenedor de tarjetas apiladas estilo Tinder -->
                    <div class="relative" style="height: 600px;">
                        <div id="cards-stack" class="relative w-full h-full">
                            @foreach($perfiles as $index => $perfil)
                                <div class="swipe-card absolute inset-0 bg-white rounded-3xl shadow-2xl overflow-hidden cursor-grab active:cursor-grabbing transition-all duration-300"
                                     data-profile-id="{{ $perfil->id }}"
                                     data-index="{{ $index }}"
                                     style="transform: translateY({{ $index * 10 }}px) scale({{ 1 - ($index * 0.03) }}); z-index: {{ 100 - $index }}; opacity: {{ $index < 3 ? 1 : 0 }};">

                                    <!-- Imagen de perfil fullscreen con carrusel -->
                                    <div class="relative h-full">
                                        @php
                                            $allPhotos = array_filter([
                                                $perfil->foto_principal,
                                                ...($perfil->fotos_adicionales ?? [])
                                            ]);
                                            $totalPhotos = count($allPhotos);
                                        @endphp

                                        <!-- Contenedor de la galería de fotos -->
                                        <div class="photo-carousel relative w-full h-full" data-card-id="{{ $perfil->id }}">
                                            @foreach($allPhotos as $photoIndex => $photo)
                                                <div class="carousel-photo absolute inset-0 transition-opacity duration-300 {{ $photoIndex === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                                                     data-photo-index="{{ $photoIndex }}">
                                                    <img src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}"
                                                         alt="{{ $perfil->nombre }}"
                                                         class="w-full h-full object-cover pointer-events-none select-none"
                                                         draggable="false">
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($totalPhotos > 1)
                                            <!-- Indicadores de foto en la parte superior (barras estilo Stories) -->
                                            <div class="absolute top-3 left-0 right-0 z-30 flex gap-1.5 px-3 pointer-events-none" data-carousel-id="{{ $perfil->id }}">
                                                @foreach($allPhotos as $photoIndex => $photo)
                                                    <div class="flex-1 h-1 rounded-full transition-all duration-300 {{ $photoIndex === 0 ? 'bg-white shadow-lg' : 'bg-white/40' }}"
                                                         data-indicator-index="{{ $photoIndex }}"></div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Overlay con gradiente -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none z-20"></div>

                                        <!-- Indicador de múltiples fotos -->
                                        @if($totalPhotos > 1)
                                            <div class="absolute top-6 right-6 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full shadow-lg flex items-center gap-1 z-40 pointer-events-none">
                                                <svg class="w-4 h-4 text-brown" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-xs font-bold text-brown">{{ $totalPhotos }}</span>
                                            </div>
                                        @endif

                                        <!-- Indicador de like/nope -->
                                        <div class="like-indicator absolute top-12 right-12 bg-green-500 text-white px-8 py-4 rounded-2xl font-black text-3xl rotate-12 border-4 border-white opacity-0 transition-opacity duration-200 z-40 pointer-events-none">
                                            ❤️ LIKE
                                        </div>
                                        <div class="nope-indicator absolute top-12 left-12 bg-red-500 text-white px-8 py-4 rounded-2xl font-black text-3xl -rotate-12 border-4 border-white opacity-0 transition-opacity duration-200 z-40 pointer-events-none">
                                            ✕ NOPE
                                        </div>

                                        <!-- Badge de ciudad -->
                                        <div class="absolute top-6 left-6 z-40 pointer-events-none">
                                            <span class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-xs font-bold text-brown shadow-lg flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $perfil->ciudad }}
                                            </span>
                                        </div>

                                        <!-- Información del perfil -->
                                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white z-40">
                                            <div class="flex items-end justify-between mb-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <h2 class="text-4xl font-black">{{ $perfil->nombre }}, {{ $perfil->edad }}</h2>
                                                        @if($perfil->verified)
                                                            <svg class="w-7 h-7 text-blue-500" fill="currentColor" viewBox="0 0 20 20" title="Perfil verificado">
                                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    @if($perfil->biografia)
                                                        <p class="text-white/90 text-sm mb-3 line-clamp-2 leading-relaxed">
                                                            {{ $perfil->biografia }}
                                                        </p>
                                                    @endif

                                                    <!-- Intereses -->
                                                    @if($perfil->intereses && count($perfil->intereses) > 0)
                                                        <div class="flex flex-wrap gap-2 mb-3">
                                                            @foreach(array_slice($perfil->intereses, 0, 4) as $interes)
                                                                <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-semibold border border-white/30">
                                                                    {{ $interes }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Botón de info -->
                                                <a href="{{ route('profile.public', $perfil->id) }}"
                                                   class="ml-4 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/30 transition border border-white/30"
                                                   onclick="event.stopPropagation();">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Botones de acción grandes -->
                        <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2 flex items-center gap-6">
                            <button id="nope-btn" class="w-16 h-16 bg-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform text-heart-red border-4 border-red-100">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            <button id="like-btn" class="w-20 h-20 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform text-white">
                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                        </div>
                    </div>

                    <!-- Contador de perfiles -->
                    <div class="text-center mt-28 text-gray-500 text-sm">
                        <span id="profiles-count">{{ count($perfiles) }}</span> perfiles disponibles
                    </div>

                @else
                    <!-- Sin perfiles disponibles -->
                    <div class="text-center py-20">
                        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-cream to-brown/10 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-brown/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-brown mb-3">No hay más perfiles</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">Has visto todos los perfiles disponibles. Vuelve más tarde para ver nuevas personas.</p>
                        <a href="{{ route('user.profile.show') }}" class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-8 py-4 rounded-full hover:shadow-glow transition font-bold shadow-lg">
                            Ver Mi Perfil
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Script para filtros (siempre disponible) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Panel de filtros
            const filtersBtn = document.getElementById('filters-btn');
            const filtersPanel = document.getElementById('filters-panel');
            const filtersOverlay = document.getElementById('filters-overlay');
            const closeFilters = document.getElementById('close-filters');
            const clearFilters = document.getElementById('clear-filters');

            function openFilters() {
                filtersPanel.classList.remove('translate-x-full');
                filtersOverlay.classList.remove('opacity-0', 'pointer-events-none');
            }

            function closeFiltersPanel() {
                filtersPanel.classList.add('translate-x-full');
                filtersOverlay.classList.add('opacity-0', 'pointer-events-none');
            }

            if (filtersBtn) filtersBtn.addEventListener('click', openFilters);
            if (closeFilters) closeFilters.addEventListener('click', closeFiltersPanel);
            if (filtersOverlay) filtersOverlay.addEventListener('click', closeFiltersPanel);

            if (clearFilters) {
                clearFilters.addEventListener('click', () => {
                    window.location.href = '{{ route('dashboard') }}';
                });
            }
        });
    </script>

    @if(count($perfiles) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardsStack = document.getElementById('cards-stack');
            const cards = Array.from(document.querySelectorAll('.swipe-card'));
            const likeBtn = document.getElementById('like-btn');
            const nopeBtn = document.getElementById('nope-btn');
            const profilesCount = document.getElementById('profiles-count');

            let currentCardIndex = 0;
            let isDragging = false;
            let startX = 0;
            let currentX = 0;

            // Verificar si hay un nuevo match para mostrar
            @if(isset($newMatch) && $newMatch)
                showMatchNotification('{{ $newMatch['photo'] }}', '{{ $newMatch['name'] }}', null);
            @endif

            function updateProfilesCount() {
                const remaining = cards.length - currentCardIndex;
                profilesCount.textContent = remaining;
            }

            function removeCard(direction) {
                if (currentCardIndex >= cards.length) return;

                const card = cards[currentCardIndex];
                const profileId = card.dataset.profileId;

                // Animación de salida
                card.style.transition = 'all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';

                if (direction === 'right') {
                    card.style.transform = 'translateX(800px) rotate(30deg)';
                    sendLike(profileId);
                } else if (direction === 'left') {
                    card.style.transform = 'translateX(-800px) rotate(-30deg)';
                }

                card.style.opacity = '0';

                setTimeout(() => {
                    card.remove();
                    currentCardIndex++;
                    updateProfilesCount();

                    // Animar las tarjetas restantes
                    updateCardsPosition();

                    // Inicializar eventos de drag para la siguiente tarjeta
                    initCardDragListeners();

                    // Si no quedan más tarjetas, recargar
                    if (currentCardIndex >= cards.length) {
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                }, 500);
            }

            function updateCardsPosition() {
                cards.forEach((card, index) => {
                    if (index >= currentCardIndex && index < currentCardIndex + 3) {
                        const relativeIndex = index - currentCardIndex;
                        card.style.transition = 'all 0.3s ease';
                        card.style.transform = `translateY(${relativeIndex * 10}px) scale(${1 - (relativeIndex * 0.03)})`;
                        card.style.zIndex = 100 - relativeIndex;
                        card.style.opacity = '1';
                    }
                });
            }

            function sendLike(profileId) {
                fetch(`/like/${profileId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        liked_user_id: profileId
                    })
                }).then(response => response.json())
                  .then(data => {
                      console.log('Response data:', data);
                      if (data.match) {
                          showMatchNotification(data.matched_user.photo, data.matched_user.name, profileId);
                      }
                  })
                  .catch(error => {
                      console.error('Error sending like:', error);
                  });
            }

            function showMatchNotification(matchedPhoto, matchedName, profileId) {
                // Crear notificación de match espectacular
                const notification = document.createElement('div');
                notification.className = 'match-notification fixed inset-0 z-[9999] flex items-center justify-center';
                notification.innerHTML = `
                    <!-- Fondo con blur y animación -->
                    <div class="absolute inset-0 bg-gradient-to-br from-heart-red/20 via-black/70 to-heart-red/20 backdrop-blur-md match-bg-fade"></div>

                    <!-- Confetti/Partículas -->
                    <div class="confetti-container absolute inset-0 pointer-events-none">
                        ${Array(30).fill().map((_, i) => `
                            <div class="confetti" style="left: ${Math.random() * 100}%; animation-delay: ${Math.random() * 0.5}s; animation-duration: ${2 + Math.random()}s;"></div>
                        `).join('')}
                    </div>

                    <!-- Contenedor principal -->
                    <div class="relative max-w-md mx-4 match-popup-scale">
                        <!-- Título IT'S A MATCH con animación -->
                        <div class="text-center mb-8 match-title-bounce">
                            <h2 class="text-6xl font-black text-white mb-2 match-text-glow" style="text-shadow: 0 0 30px rgba(198, 40, 40, 0.8), 0 0 60px rgba(198, 40, 40, 0.5);">
                                ¡ES UN MATCH!
                            </h2>
                            <p class="text-white/90 text-lg">💕 Os gustáis mutuamente 💕</p>
                        </div>

                        <!-- Fotos de los perfiles con animación -->
                        <div class="relative h-64 mb-8">
                            <!-- Foto del match (izquierda) -->
                            <div class="absolute left-0 top-0 w-40 h-56 match-photo-left">
                                <div class="relative w-full h-full rounded-2xl overflow-hidden border-4 border-white shadow-2xl transform -rotate-6">
                                    <img src="${matchedPhoto}" class="w-full h-full object-cover" alt="${matchedName}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-2 left-2 right-2">
                                        <p class="text-white font-black text-lg truncate">${matchedName}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Foto tuya (derecha) -->
                            <div class="absolute right-0 top-0 w-40 h-56 match-photo-right">
                                <div class="relative w-full h-full rounded-2xl overflow-hidden border-4 border-white shadow-2xl transform rotate-6">
                                    @php
                                        $myProfile = Auth::user()->profile;
                                        $myPhoto = $myProfile->foto_principal ?? null;
                                    @endphp
                                    @if($myPhoto)
                                        <img src="{{ str_starts_with($myPhoto, 'http') ? $myPhoto : Storage::url($myPhoto) }}" class="w-full h-full object-cover" alt="{{ Auth::user()->name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-heart-red to-heart-red-light flex items-center justify-center text-white text-6xl font-black">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-2 left-2 right-2">
                                        <p class="text-white font-black text-lg truncate">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Corazón central con pulso -->
                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                                <div class="w-20 h-20 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center shadow-2xl match-heart-pulse">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Mensaje motivacional -->
                        <div class="bg-white rounded-3xl p-6 shadow-2xl match-message-fade mb-6">
                            <p class="text-brown text-center font-bold mb-2">
                                ¡Felicidades! 🎉
                            </p>
                            <p class="text-gray-600 text-center text-sm">
                                Ahora podéis empezar a conoceros. ¡No seas tímido y envía el primer mensaje!
                            </p>
                        </div>

                        <!-- Botones de acción -->
                        <div class="grid grid-cols-2 gap-4 match-buttons-slide">
                            <a href="/matches" class="bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-full font-black text-center hover:scale-105 transition shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Enviar Mensaje
                            </a>
                            <button onclick="this.closest('.match-notification').remove()" class="bg-white text-brown border-2 border-brown py-4 px-6 rounded-full font-black text-center hover:bg-brown hover:text-white transition shadow-lg">
                                Seguir Descubriendo
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
            }

            // Event listeners para botones
            likeBtn.addEventListener('click', () => removeCard('right'));
            nopeBtn.addEventListener('click', () => removeCard('left'));

            // Drag functionality - Inicializar eventos para todas las tarjetas
            function initCardDragListeners() {
                // Remover listeners antiguos de todas las tarjetas
                cards.forEach(card => {
                    card.removeEventListener('mousedown', handleDragStart);
                    card.removeEventListener('touchstart', handleDragStart);
                });

                // Agregar listeners solo a la tarjeta actual
                if (currentCardIndex < cards.length) {
                    const currentCard = cards[currentCardIndex];
                    currentCard.addEventListener('mousedown', handleDragStart);
                    currentCard.addEventListener('touchstart', handleDragStart);
                }
            }

            // Inicializar la primera tarjeta
            initCardDragListeners();

            // ==================== CARRUSEL DE FOTOS ====================
            // Almacenar el índice actual de foto para cada tarjeta
            const cardPhotoIndexes = {};

            // Inicializar índices para todas las tarjetas
            cards.forEach(card => {
                const cardId = card.dataset.profileId;
                cardPhotoIndexes[cardId] = 0;
            });

            // Función para navegar entre fotos
            function navigatePhoto(cardId, direction) {
                const carousel = document.querySelector(`.photo-carousel[data-card-id="${cardId}"]`);
                if (!carousel) return;

                const photos = carousel.querySelectorAll('.carousel-photo');
                const indicators = document.querySelector(`[data-carousel-id="${cardId}"]`)?.querySelectorAll('[data-indicator-index]');

                if (!photos || photos.length <= 1) return;

                const currentIndex = cardPhotoIndexes[cardId] || 0;
                let newIndex;

                if (direction === 'next') {
                    newIndex = (currentIndex + 1) % photos.length;
                } else {
                    newIndex = currentIndex === 0 ? photos.length - 1 : currentIndex - 1;
                }

                // Ocultar foto actual
                photos[currentIndex].classList.remove('opacity-100', 'z-10');
                photos[currentIndex].classList.add('opacity-0', 'z-0');

                // Mostrar nueva foto
                photos[newIndex].classList.remove('opacity-0', 'z-0');
                photos[newIndex].classList.add('opacity-100', 'z-10');

                // Actualizar indicadores
                if (indicators) {
                    indicators[currentIndex].classList.remove('bg-white', 'shadow-lg');
                    indicators[currentIndex].classList.add('bg-white/40');

                    indicators[newIndex].classList.remove('bg-white/40');
                    indicators[newIndex].classList.add('bg-white', 'shadow-lg');
                }

                // Guardar nuevo índice
                cardPhotoIndexes[cardId] = newIndex;
            }

            // Navegación con teclas A (anterior) y D (siguiente)
            document.addEventListener('keydown', (e) => {
                if (currentCardIndex >= cards.length) return;

                const currentCard = cards[currentCardIndex];
                const cardId = currentCard.dataset.profileId;

                if (e.key === 'a' || e.key === 'A') {
                    navigatePhoto(cardId, 'prev');
                } else if (e.key === 'd' || e.key === 'D') {
                    navigatePhoto(cardId, 'next');
                }
            });
            // ==================== FIN CARRUSEL DE FOTOS ====================

            let hasMoved = false; // Track si realmente se ha movido la tarjeta
            let clickStartX = 0; // Posición X inicial del click
            let clickStartY = 0; // Posición Y inicial del click

            function handleDragStart(e) {
                if (currentCardIndex >= cards.length) return;

                // Prevenir comportamiento de drag & drop nativo del navegador
                e.preventDefault();

                const card = cards[currentCardIndex];
                card.style.transition = 'none';

                isDragging = true;
                hasMoved = false;
                startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
                clickStartX = startX;
                clickStartY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;

                document.addEventListener('mousemove', handleDragMove);
                document.addEventListener('touchmove', handleDragMove);
                document.addEventListener('mouseup', handleDragEnd);
                document.addEventListener('touchend', handleDragEnd);
            }

            function handleDragMove(e) {
                if (!isDragging) return;

                currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
                const deltaX = currentX - startX;

                // Solo activar el drag si se ha movido al menos 10px
                if (Math.abs(deltaX) < 10 && !hasMoved) return;

                hasMoved = true; // Marcar que se ha iniciado el movimiento

                const card = cards[currentCardIndex];
                const rotation = deltaX / 20;

                card.style.transform = `translateX(${deltaX}px) rotate(${rotation}deg)`;

                // Mostrar indicadores
                const likeIndicator = card.querySelector('.like-indicator');
                const nopeIndicator = card.querySelector('.nope-indicator');

                if (deltaX > 50) {
                    likeIndicator.style.opacity = Math.min(deltaX / 150, 1);
                    nopeIndicator.style.opacity = 0;
                } else if (deltaX < -50) {
                    nopeIndicator.style.opacity = Math.min(Math.abs(deltaX) / 150, 1);
                    likeIndicator.style.opacity = 0;
                } else {
                    likeIndicator.style.opacity = 0;
                    nopeIndicator.style.opacity = 0;
                }
            }

            function handleDragEnd(e) {
                if (!isDragging) return;

                isDragging = false;
                const card = cards[currentCardIndex];

                document.removeEventListener('mousemove', handleDragMove);
                document.removeEventListener('touchmove', handleDragMove);
                document.removeEventListener('mouseup', handleDragEnd);
                document.removeEventListener('touchend', handleDragEnd);

                // Si nunca se movió, fue un click - activar carrusel
                if (!hasMoved) {
                    const endX = e.type === 'mouseup' ? e.clientX : e.changedTouches[0].clientX;
                    const endY = e.type === 'mouseup' ? e.clientY : e.changedTouches[0].clientY;

                    // Verificar que sea realmente un click (no movimiento accidental)
                    const distance = Math.sqrt(Math.pow(endX - clickStartX, 2) + Math.pow(endY - clickStartY, 2));

                    if (distance < 10) {
                        // Es un click real - navegar en el carrusel
                        const cardRect = card.getBoundingClientRect();
                        const clickX = endX - cardRect.left;
                        const cardWidth = cardRect.width;
                        const cardId = card.dataset.profileId;

                        if (clickX < cardWidth / 2) {
                            navigatePhoto(cardId, 'prev');
                        } else {
                            navigatePhoto(cardId, 'next');
                        }
                    }
                    return;
                }

                // Fue un drag - procesar like/nope
                const deltaX = currentX - startX;

                if (Math.abs(deltaX) > 150) {
                    removeCard(deltaX > 0 ? 'right' : 'left');
                } else {
                    card.style.transition = 'all 0.3s ease';
                    card.style.transform = 'translateX(0) rotate(0)';
                    card.querySelector('.like-indicator').style.opacity = 0;
                    card.querySelector('.nope-indicator').style.opacity = 0;
                }
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') removeCard('right');
                if (e.key === 'ArrowLeft') removeCard('left');
            });

            // ==================== POLLING PARA NUEVOS MATCHES ====================
            let lastCheckTime = new Date().toISOString();
            let isCheckingMatches = false;

            // Función para verificar nuevos matches
            async function checkForNewMatches() {
                if (isCheckingMatches) return; // Evitar múltiples peticiones simultáneas

                isCheckingMatches = true;

                try {
                    const response = await fetch(`{{ route('matches.check-new') }}?last_check=${encodeURIComponent(lastCheckTime)}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (data.has_new_matches && data.matches.length > 0) {
                        // Mostrar popup para cada nuevo match
                        data.matches.forEach(match => {
                            showMatchNotification(match.photo, match.name, match.match_id);
                        });

                        // Actualizar el tiempo del último check
                        lastCheckTime = new Date().toISOString();
                    }
                } catch (error) {
                    console.error('Error al verificar nuevos matches:', error);
                } finally {
                    isCheckingMatches = false;
                }
            }

            // Verificar nuevos matches cada 5 segundos
            setInterval(checkForNewMatches, 5000);

            // También verificar al inicio (después de 2 segundos para dar tiempo a cargar)
            setTimeout(checkForNewMatches, 2000);
            // ==================== FIN POLLING ====================
        });
    </script>

    <style>
        /* Animaciones para el pop-up de match */
        @keyframes matchBgFade {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes matchPopupScale {
            from {
                transform: scale(0.5);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes matchTitleBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes matchPhotoSlideLeft {
            from {
                transform: translateX(-200px) rotate(-6deg);
                opacity: 0;
            }
            to {
                transform: translateX(0) rotate(-6deg);
                opacity: 1;
            }
        }

        @keyframes matchPhotoSlideRight {
            from {
                transform: translateX(200px) rotate(6deg);
                opacity: 0;
            }
            to {
                transform: translateX(0) rotate(6deg);
                opacity: 1;
            }
        }

        @keyframes matchHeartPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(198, 40, 40, 0.7);
            }
            50% {
                transform: scale(1.2);
                box-shadow: 0 0 0 20px rgba(198, 40, 40, 0);
            }
        }

        @keyframes matchMessageFade {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes matchButtonsSlide {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        @keyframes matchTextGlow {
            0%, 100% {
                text-shadow: 0 0 30px rgba(198, 40, 40, 0.8), 0 0 60px rgba(198, 40, 40, 0.5);
            }
            50% {
                text-shadow: 0 0 40px rgba(198, 40, 40, 1), 0 0 80px rgba(198, 40, 40, 0.7), 0 0 100px rgba(198, 40, 40, 0.5);
            }
        }

        /* Aplicar animaciones */
        .match-bg-fade {
            animation: matchBgFade 0.5s ease-out;
        }

        .match-popup-scale {
            animation: matchPopupScale 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .match-title-bounce {
            animation: matchTitleBounce 1s ease-in-out 0.3s;
        }

        .match-text-glow {
            animation: matchTextGlow 2s ease-in-out infinite;
        }

        .match-photo-left {
            animation: matchPhotoSlideLeft 0.8s ease-out 0.4s backwards;
        }

        .match-photo-right {
            animation: matchPhotoSlideRight 0.8s ease-out 0.5s backwards;
        }

        .match-heart-pulse {
            animation: matchHeartPulse 1.5s ease-in-out 0.6s infinite;
        }

        .match-message-fade {
            animation: matchMessageFade 0.6s ease-out 1s backwards;
        }

        .match-buttons-slide {
            animation: matchButtonsSlide 0.6s ease-out 1.2s backwards;
        }

        /* Confetti */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: linear-gradient(45deg, #C62828, #E53935, #F7EEDC, #A67C52);
            animation: confettiFall 3s linear infinite;
            top: -10%;
        }

        .confetti:nth-child(odd) {
            background: linear-gradient(45deg, #C62828, #E53935);
            border-radius: 50%;
        }

        .confetti:nth-child(even) {
            background: linear-gradient(45deg, #F7EEDC, #A67C52);
        }

        .confetti:nth-child(3n) {
            width: 8px;
            height: 8px;
        }

        .confetti:nth-child(4n) {
            width: 12px;
            height: 12px;
        }
    </style>
    @endif
</x-app-layout>
