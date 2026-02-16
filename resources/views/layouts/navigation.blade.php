{{-- ===== HEADER MINIMALISTA CON LOGO ===== --}}
<header class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-12 h-12">
            </a>

            {{-- Acciones rapidas del header --}}
            <div class="flex items-center gap-3">
                {{-- Notificaciones --}}
                <div x-data="{ unreadCount: 0 }" x-init="
                    fetch('{{ route('notifications.count') }}')
                        .then(res => res.json())
                        .then(data => unreadCount = data.count);
                    setInterval(() => {
                        fetch('{{ route('notifications.count') }}')
                            .then(res => res.json())
                            .then(data => unreadCount = data.count);
                    }, 30000);
                " class="relative">
                    <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center p-2 text-gray-500 hover:text-heart-red transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-heart-red rounded-full min-w-[1.1rem]"></span>
                    </a>
                </div>

                {{-- Menu de opciones (simplificado) --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="p-2 text-gray-500 hover:text-heart-red transition rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>

                    {{-- Dropdown simplificado --}}
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                         style="display: none;">

                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/></svg>
                                Panel Admin
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-heart-red transition w-full text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar Sesion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- ===== BARRA DE NAVEGACION INFERIOR (GLOBAL) ===== --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 shadow-lg" id="bottom-nav"
     x-data="{
        unreadMessages: 0,
        newMatches: 0
     }"
     x-init="
        // Cargar contadores iniciales
        fetch('/api/unread-messages-count')
            .then(res => res.json())
            .then(data => unreadMessages = data.count)
            .catch(() => {});

        fetch('/api/new-matches-count')
            .then(res => res.json())
            .then(data => newMatches = data.count)
            .catch(() => {});

        // Actualizar cada 30 segundos
        setInterval(() => {
            fetch('/api/unread-messages-count')
                .then(res => res.json())
                .then(data => unreadMessages = data.count)
                .catch(() => {});

            fetch('/api/new-matches-count')
                .then(res => res.json())
                .then(data => newMatches = data.count)
                .catch(() => {});
        }, 30000);
     ">
    <div class="max-w-4xl mx-auto flex items-center justify-around py-2">
        {{-- Inicio --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('dashboard') ? 'opacity-100' : 'opacity-60 hover:opacity-100' }}">
            <img src="{{ asset('images/LOGOCITAS.png') }}" alt="Citas Mallorca" class="w-7 h-7">
            <span class="text-[10px] font-semibold {{ request()->routeIs('dashboard') ? 'text-heart-red' : 'text-gray-400' }}">Inicio</span>
        </a>

        {{-- Matches (reemplaza Buscar) --}}
        <a href="{{ route('matches') }}" class="relative flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('matches*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('matches*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span x-show="newMatches > 0" x-text="newMatches" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-heart-red rounded-full min-w-[1.1rem]"></span>
            <span class="text-[10px] font-semibold">Matches</span>
        </a>

        {{-- Mensajes --}}
        <a href="{{ route('messages') }}" class="relative flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('messages*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('messages*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span x-show="unreadMessages > 0" x-text="unreadMessages" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-heart-red rounded-full min-w-[1.1rem]"></span>
            <span class="text-[10px] font-semibold">Mensajes</span>
        </a>

        {{-- Likes (siempre visible) --}}
        @php
            $hasActiveSubscription = Auth::user()->activeSubscription !== null;
        @endphp

        <div x-data="{ likesOpen: false }" class="relative">
            <button @click="likesOpen = !likesOpen" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('likes.*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
                <svg class="w-6 h-6" fill="{{ request()->routeIs('likes.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="text-[10px] font-semibold">Likes</span>
            </button>

            {{-- Menu desplegable de Likes --}}
            <div x-show="likesOpen" @click.away="likesOpen = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-1.5 z-[60]"
                 style="display: none;">

                @if($hasActiveSubscription)
                    {{-- Con suscripcion: acceso completo --}}
                    <a href="{{ route('likes.who') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-cream transition">
                        <svg class="w-3.5 h-3.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        <span>Quien te ha dado like</span>
                    </a>
                @else
                    {{-- Sin suscripcion: bloqueado con mensaje --}}
                    <div class="px-3 py-2 text-xs text-gray-400 cursor-not-allowed">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Quien te ha dado like</span>
                        </div>
                        <a href="{{ route('subscriptions.index') }}" class="block mt-1 ml-5 text-[10px] text-heart-red hover:underline font-semibold">
                            Contrata un plan para desbloquear
                        </a>
                    </div>
                @endif

                <a href="{{ route('likes.my') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-cream transition">
                    <svg class="w-3.5 h-3.5 text-heart-red" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    <span>Mis Likes</span>
                </a>
            </div>
        </div>

        {{-- Planes --}}
        <a href="{{ route('subscriptions.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('subscriptions.*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('subscriptions.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <span class="text-[10px] font-semibold">Planes</span>
        </a>

        {{-- Mi perfil --}}
        <a href="{{ route('user.profile.show') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('user.profile.*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            @php
                $userProfile = Auth::user()->profile;
                $userPhoto = $userProfile && $userProfile->foto_principal
                    ? (str_starts_with($userProfile->foto_principal, 'http')
                        ? $userProfile->foto_principal
                        : Storage::url($userProfile->foto_principal))
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=A67C52&color=fff';
            @endphp
            <img src="{{ $userPhoto }}"
                 alt="{{ Auth::user()->name }}"
                 class="w-7 h-7 rounded-full object-cover {{ request()->routeIs('user.profile.*') ? 'ring-2 ring-heart-red' : '' }}">
            <span class="text-[10px] font-semibold">Mi perfil</span>
        </a>
    </div>
    {{-- Safe area para moviles con notch --}}
    <div class="h-[env(safe-area-inset-bottom)]"></div>
</nav>
