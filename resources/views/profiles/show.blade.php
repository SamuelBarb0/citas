@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cream py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Mensajes -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6 text-sm sm:text-base">
                {{ session('info') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm sm:text-base">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tarjeta de Perfil -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header con foto de fondo -->
            <div class="h-32 sm:h-48 bg-gradient-to-r from-brown to-heart-red"></div>

            <!-- Contenido del perfil -->
            <div class="px-4 sm:px-8 pb-8">
                <!-- Foto de perfil -->
                <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-20 mb-6">
                    <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white bg-white overflow-hidden shadow-xl mb-4 sm:mb-0">
                        @if($profile->foto_principal)
                            <img src="{{ str_starts_with($profile->foto_principal, 'http') ? $profile->foto_principal : Storage::url($profile->foto_principal) }}" alt="{{ $profile->nombre }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="text-center sm:text-left sm:ml-6 flex-1">
                        <h1 class="text-3xl sm:text-4xl font-bold text-brown">{{ $profile->nombre }}</h1>
                        <p class="text-gray-600 mt-1 text-sm sm:text-base">{{ $profile->edad }} años • {{ $profile->ciudad }}</p>
                    </div>

                    <a href="{{ route('user.profile.edit') }}" class="mt-4 sm:mt-0 bg-brown text-white px-6 py-2 rounded-full hover:bg-opacity-90 transition font-medium text-sm sm:text-base">
                        Editar Perfil
                    </a>
                </div>

                <!-- Información -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Info básica -->
                    <div class="bg-cream p-4 rounded-xl">
                        <h3 class="font-semibold text-brown mb-2 text-sm sm:text-base">Género</h3>
                        <p class="text-gray-700 capitalize text-sm">{{ $profile->genero }}</p>
                    </div>

                    <div class="bg-cream p-4 rounded-xl">
                        <h3 class="font-semibold text-brown mb-2 text-sm sm:text-base">Buscando</h3>
                        <p class="text-gray-700 capitalize text-sm">{{ $profile->busco }}</p>
                    </div>

                    <div class="bg-cream p-4 rounded-xl">
                        <h3 class="font-semibold text-brown mb-2 text-sm sm:text-base">Estado</h3>
                        <p class="text-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $profile->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $profile->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Verificación del Perfil -->
                @php
                    $hasPendingRequest = \App\Models\VerificationRequest::where('user_id', Auth::id())
                        ->where('estado', 'pendiente')
                        ->exists();
                @endphp

                @if($profile->verified)
                    <!-- Perfil verificado -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-green-800 font-medium">Cuenta verificada - Has completado el proceso de validación de identidad</p>
                        </div>
                    </div>
                @elseif($hasPendingRequest)
                    <!-- Solicitud pendiente -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-yellow-800 font-medium">Verificación en proceso - Tu solicitud está siendo revisada (24-48h)</p>
                        </div>
                    </div>
                @else
                    <!-- No verificado - Call to action -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-blue-800 font-medium">Verifica tu identidad para evitar que tu cuenta sea marcada como sospechosa</p>
                            </div>
                            <a href="{{ route('verification.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm whitespace-nowrap">
                                Verificar ahora
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Sobre mí -->
                @if($profile->biografia)
                <div class="mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-brown mb-4">Sobre mí</h2>
                    <p class="text-gray-700 leading-relaxed text-sm sm:text-base">{{ $profile->biografia }}</p>
                </div>
                @endif

                <!-- Intereses -->
                @if($profile->intereses && count($profile->intereses) > 0)
                <div class="mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-brown mb-4">Mis Intereses</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->intereses as $interes)
                            <span class="bg-cream text-brown px-4 py-2 rounded-full font-medium text-xs sm:text-sm">
                                {{ ucfirst($interes) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Estadísticas -->
                <div class="grid grid-cols-3 gap-4 py-6 border-t border-gray-200">
                    <div class="text-center">
                        <p class="text-2xl sm:text-3xl font-bold text-heart-red">0</p>
                        <p class="text-gray-600 text-xs sm:text-sm">Matches</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl sm:text-3xl font-bold text-heart-red">0</p>
                        <p class="text-gray-600 text-xs sm:text-sm">Me gusta</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl sm:text-3xl font-bold text-heart-red">0</p>
                        <p class="text-gray-600 text-xs sm:text-sm">Visitas</p>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <a href="{{ route('dashboard') }}" class="bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-center text-sm sm:text-base shadow-lg">
                        Descubrir Perfiles
                    </a>
                    <a href="{{ route('matches') }}" class="bg-white text-brown border-2 border-brown py-3 px-6 rounded-full hover:bg-brown hover:text-white transition font-semibold text-center text-sm sm:text-base">
                        Ver Matches
                    </a>
                </div>
            </div>
        </div>

        <!-- Consejos -->
        <div class="mt-6 bg-white rounded-xl p-4 sm:p-6 shadow-lg">
            <h3 class="font-semibold text-brown mb-3 text-sm sm:text-base">💡 Consejos para tu perfil</h3>
            <ul class="space-y-2 text-gray-600 text-xs sm:text-sm">
                <li class="flex items-start">
                    <span class="text-heart-red mr-2">•</span>
                    <span>Añade una foto reciente y clara para tener más visitas</span>
                </li>
                <li class="flex items-start">
                    <span class="text-heart-red mr-2">•</span>
                    <span>Una biografía interesante aumenta tus matches</span>
                </li>
                <li class="flex items-start">
                    <span class="text-heart-red mr-2">•</span>
                    <span>Completa todos tus intereses para mejores coincidencias</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
