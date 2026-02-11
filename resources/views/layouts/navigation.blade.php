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

                {{-- Menu de opciones --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="p-2 text-gray-500 hover:text-heart-red transition rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                         style="display: none;">

                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="font-semibold text-brown text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                        </div>

                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/></svg>
                                Panel Admin
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                        @endif

                        <a href="{{ route('likes.who') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                            <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                            Quien te ha dado like
                        </a>
                        <a href="{{ route('likes.my') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                            <svg class="w-4 h-4 text-heart-red" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                            Mis Likes
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <a href="{{ route('subscriptions.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                            <svg class="w-4 h-4 text-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Mi Suscripcion
                        </a>
                        <a href="{{ route('blocked.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-cream transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            Usuarios Bloqueados
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

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
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 shadow-lg" id="bottom-nav">
    <div class="max-w-4xl mx-auto flex items-center justify-around py-2">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('dashboard') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] font-semibold">Inicio</span>
        </a>
        <a href="{{ route('dashboard') }}?buscar=1" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition text-gray-400 hover:text-brown">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-[10px] font-semibold">Buscar</span>
        </a>
        <a href="{{ route('messages') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('messages*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('messages*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="text-[10px] font-semibold">Mensajes</span>
        </a>
        <a href="{{ route('subscriptions.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('subscriptions.index') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('subscriptions.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <span class="text-[10px] font-semibold">Planes</span>
        </a>
        <a href="{{ route('user.profile.show') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-lg transition {{ request()->routeIs('user.profile.*') ? 'text-heart-red' : 'text-gray-400 hover:text-brown' }}">
            <svg class="w-6 h-6" fill="{{ request()->routeIs('user.profile.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px] font-semibold">Mi perfil</span>
        </a>
    </div>
    {{-- Safe area para moviles con notch --}}
    <div class="h-[env(safe-area-inset-bottom)]"></div>
</nav>
