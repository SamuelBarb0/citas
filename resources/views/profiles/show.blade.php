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
