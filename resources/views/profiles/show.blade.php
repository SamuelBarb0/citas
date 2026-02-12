@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cream">
    {{-- Notificaciones temporales --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-4 right-4 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed top-4 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    @php
        $allPhotos = array_filter([
            $profile->foto_principal,
            ...($profile->fotos_adicionales ?? [])
        ]);
        $user = Auth::user();
        $hasPendingRequest = \App\Models\VerificationRequest::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->exists();
        $likesReceived = \App\Models\Like::where('liked_user_id', $user->id)->count();
    @endphp

    <div class="max-w-lg mx-auto px-4 py-6 space-y-4">

        {{-- ===== TARJETA DE PERFIL PRINCIPAL (centrada como mockup) ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                {{-- Foto centrada + info --}}
                <div class="flex flex-col items-center text-center">
                    {{-- Foto de perfil grande centrada --}}
                    <div class="relative mb-3">
                        @if($profile->foto_principal)
                            <img src="{{ str_starts_with($profile->foto_principal, 'http') ? $profile->foto_principal : Storage::url($profile->foto_principal) }}"
                                 alt="{{ $profile->nombre }}"
                                 class="w-28 h-28 rounded-full object-cover shadow-lg border-4 border-white ring-2 ring-heart-red/20">
                        @else
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-brown to-heart-red flex items-center justify-center shadow-lg border-4 border-white">
                                <svg class="w-14 h-14 text-white/40" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Badge verificado --}}
                        @if($profile->verified)
                            <div class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-1.5 shadow-md border-2 border-white">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Nombre y edad --}}
                    <h1 class="text-2xl font-black text-brown">{{ $profile->nombre }}, {{ $profile->edad }}</h1>

                    {{-- Ciudad --}}
                    <div class="flex items-center gap-1 text-gray-500 text-sm mt-1">
                        <svg class="w-3.5 h-3.5 text-heart-red" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $profile->ciudad }}</span>
                    </div>

                    {{-- Boton editar perfil --}}
                    <a href="{{ route('user.profile.edit') }}"
                       class="mt-4 w-full block text-center bg-gradient-to-r from-brown to-heart-red text-white py-2.5 rounded-xl font-semibold text-sm hover:shadow-lg transition">
                        Editar perfil
                    </a>
                </div>
            </div>
        </div>

        {{-- Verificacion --}}
        @if(!$profile->verified && !$hasPendingRequest)
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-sm text-blue-800 font-medium">Verifica tu perfil para ganar confianza</p>
                </div>
                <a href="{{ route('verification.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-semibold transition text-xs whitespace-nowrap">
                    Verificar
                </a>
            </div>
        @elseif($hasPendingRequest)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm text-yellow-800 font-medium">Verificacion en proceso (24-48h)</p>
            </div>
        @endif

        {{-- ===== MIS FOTOS ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-brown">Mis Fotos</h2>
                    <a href="{{ route('user.profile.edit') }}" class="text-heart-red text-sm font-semibold hover:underline">Gestionar</a>
                </div>

                @if(count($allPhotos) > 0)
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($allPhotos as $index => $photo)
                            <div class="relative aspect-square rounded-xl overflow-hidden cursor-pointer group" onclick="openFullscreen({{ $index }})">
                                <img src="{{ str_starts_with($photo, 'http') ? $photo : Storage::url($photo) }}"
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($index === 0)
                                    <div class="absolute bottom-1 left-1 bg-brown/80 text-white text-[10px] px-1.5 py-0.5 rounded-full font-semibold">
                                        Principal
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        @php $maxFotos = $user->getMaxFotosAdicionales() + 1; @endphp
                        @if(count($allPhotos) < $maxFotos)
                            <a href="{{ route('user.profile.edit') }}" class="aspect-square rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center hover:border-heart-red hover:bg-red-50 transition group">
                                <svg class="w-8 h-8 text-gray-300 group-hover:text-heart-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                @else
                    <a href="{{ route('user.profile.edit') }}" class="block text-center py-8 border-2 border-dashed border-gray-300 rounded-xl hover:border-heart-red transition">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-400 text-sm font-medium">Sube tus fotos</p>
                    </a>
                @endif
            </div>
        </div>

        {{-- ===== SOBRE MI ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-black text-brown">Sobre mi</h2>
                    <a href="{{ route('user.profile.edit') }}" class="text-heart-red text-sm font-semibold hover:underline">Editar</a>
                </div>
                @if($profile->biografia)
                    <p class="text-gray-700 leading-relaxed text-sm">{{ $profile->biografia }}</p>
                @else
                    <p class="text-gray-400 text-sm italic">Aun no has escrito tu biografia.
                        <a href="{{ route('user.profile.edit') }}" class="text-heart-red font-semibold hover:underline">Escribe una</a>
                    </p>
                @endif

                {{-- Buscando --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-heart-red flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-600">Buscando:</span>
                    <span class="font-semibold text-brown">
                        @if($profile->busco === 'hombre') Hombres
                        @elseif($profile->busco === 'mujer') Mujeres
                        @elseif($profile->busco === 'persona_no_binaria') Personas no binarias
                        @elseif($profile->busco === 'genero_fluido') Personas de genero fluido
                        @elseif($profile->busco === 'cualquiera') Cualquiera
                        @else {{ ucfirst(str_replace('_', ' ', $profile->busco)) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- ===== MIS INTERESES ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-brown">Mis Intereses</h2>
                    <a href="{{ route('user.profile.edit') }}" class="text-heart-red text-sm font-semibold hover:underline">Editar</a>
                </div>

                @if($profile->intereses && count($profile->intereses) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->intereses as $interes)
                            <span class="bg-cream text-brown px-4 py-2 rounded-full font-semibold text-sm border border-brown/10">
                                {{ ucfirst($interes) }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm italic">No has anadido intereses todavia.
                        <a href="{{ route('user.profile.edit') }}" class="text-heart-red font-semibold hover:underline">Anadir</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- ===== SEPARADOR: CONFIGURACION Y CUENTA ===== --}}
        <div class="mt-8 mb-4 flex items-center gap-3">
            <div class="h-px bg-gray-200 flex-1"></div>
            <h3 class="text-sm font-black text-gray-400 uppercase tracking-wider">Configuración y Cuenta</h3>
            <div class="h-px bg-gray-200 flex-1"></div>
        </div>

        {{-- ===== MI SUSCRIPCION ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <a href="{{ route('subscriptions.dashboard') }}" class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-brown to-brown-dark rounded-full flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-brown text-sm">Mi Suscripción</h3>
                            @if($subscription && $plan)
                                <p class="text-xs text-green-600 font-semibold">{{ $plan->nombre }} · Activa</p>
                            @else
                                <p class="text-xs text-gray-500">Sin plan activo</p>
                            @endif
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-heart-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- ===== USUARIOS BLOQUEADOS ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden">
            <div class="p-5">
                <a href="{{ route('blocked.index') }}" class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-gray-200 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 15.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-brown text-sm">Usuarios Bloqueados</h3>
                            <p class="text-xs text-gray-500">Gestiona tus bloqueos</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-heart-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- ===== CERRAR SESION ===== --}}
        <div class="bg-white rounded-2xl shadow-smooth overflow-hidden border border-red-100">
            <div class="p-5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center group-hover:bg-red-100 transition">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h3 class="font-bold text-red-600 text-sm">Cerrar Sesión</h3>
                                <p class="text-xs text-gray-500">Salir de tu cuenta</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- ===== MODAL FULLSCREEN PARA FOTOS ===== --}}
<script>
    const allPhotoSrcs = @json(array_values(array_map(function($photo) {
        return str_starts_with($photo, 'http') ? $photo : Storage::url($photo);
    }, $allPhotos)));

    let currentFsIndex = 0;

    function openFullscreen(index) {
        currentFsIndex = index;
        const modal = document.createElement('div');
        modal.id = 'fullscreen-modal';
        modal.className = 'fixed inset-0 bg-black/95 z-[9999] flex items-center justify-center';
        modal.innerHTML = `
            <button onclick="closeFullscreen()" class="absolute top-4 right-4 z-50 text-white/80 hover:text-white transition p-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            ${allPhotoSrcs.length > 1 ? `
                <button onclick="fsNav(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 z-50 text-white/70 hover:text-white transition bg-white/10 hover:bg-white/20 rounded-full p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="fsNav(1)" class="absolute right-3 top-1/2 -translate-y-1/2 z-50 text-white/70 hover:text-white transition bg-white/10 hover:bg-white/20 rounded-full p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div class="absolute bottom-6 left-0 right-0 z-50 flex justify-center gap-1.5" id="fs-dots">
                    ${allPhotoSrcs.map((_, i) => `<div class="w-2 h-2 rounded-full transition-all ${i === index ? 'bg-white w-6' : 'bg-white/40'}"></div>`).join('')}
                </div>
            ` : ''}
            <img id="fs-image" src="${allPhotoSrcs[index]}" class="max-w-full max-h-full object-contain p-4" alt="Foto">
        `;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeFullscreen();
        });

        document.addEventListener('keydown', fsKeyHandler);
    }

    function closeFullscreen() {
        const modal = document.getElementById('fullscreen-modal');
        if (modal) { modal.remove(); document.body.style.overflow = ''; }
        document.removeEventListener('keydown', fsKeyHandler);
    }

    function fsNav(dir) {
        currentFsIndex = (currentFsIndex + dir + allPhotoSrcs.length) % allPhotoSrcs.length;
        document.getElementById('fs-image').src = allPhotoSrcs[currentFsIndex];
        const dots = document.querySelectorAll('#fs-dots > div');
        dots.forEach((d, i) => {
            d.className = i === currentFsIndex ? 'w-6 h-2 rounded-full transition-all bg-white' : 'w-2 h-2 rounded-full transition-all bg-white/40';
        });
    }

    function fsKeyHandler(e) {
        if (e.key === 'Escape') closeFullscreen();
        else if (e.key === 'ArrowLeft') fsNav(-1);
        else if (e.key === 'ArrowRight') fsNav(1);
    }
</script>
@endsection
