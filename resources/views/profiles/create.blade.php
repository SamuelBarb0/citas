@extends('layouts.app')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #A67C52;
        outline: none;
        box-shadow: 0 0 0 2px rgba(166, 124, 82, 0.2);
    }
    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem;
    }
    .select2-results__option--highlighted {
        background-color: #A67C52 !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-cream py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-brown mb-2">Crea tu Perfil</h1>
            <p class="text-gray-600 text-sm sm:text-base">Completa tu información para empezar a conocer gente en Mallorca</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <form action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Foto de Perfil -->
                <div class="text-center">
                    <label class="block text-sm font-semibold text-brown mb-3">Foto de Perfil</label>
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div id="preview-container" class="w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-4">
                                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <img id="preview-image" src="" alt="Preview" class="hidden w-full h-full object-cover">
                            </div>
                        </div>
                        <label for="foto_principal" class="cursor-pointer bg-brown text-white px-6 py-2 rounded-full hover:bg-opacity-90 transition text-sm sm:text-base">
                            Subir Foto
                        </label>
                        <input type="file" id="foto_principal" name="foto_principal" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG (máx. 2MB)</p>
                    </div>
                    @error('foto_principal')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-brown mb-2">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                    @error('nombre')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Edad -->
                <div>
                    <label for="edad" class="block text-sm font-semibold text-brown mb-2">Edad</label>
                    <input type="number" id="edad" name="edad" value="{{ old('edad') }}" min="18" max="100" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                    @error('edad')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Género y Orientación Sexual en Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Género (Identidad) -->
                    <div>
                        <label for="genero" class="block text-sm font-semibold text-brown mb-2">Identidad de Género</label>
                        <x-dynamic-select
                            tipo="genero"
                            name="genero"
                            id="genero"
                            :required="true"
                            :selected="old('genero', $profileData['genero'] ?? '')"
                            placeholder="Selecciona..."
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base"
                        />
                        @error('genero')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Orientación Sexual -->
                    <div>
                        <label for="orientacion_sexual" class="block text-sm font-semibold text-brown mb-2">Orientación Sexual</label>
                        <x-dynamic-select
                            tipo="orientacion_sexual"
                            name="orientacion_sexual"
                            id="orientacion_sexual"
                            :required="false"
                            :selected="old('orientacion_sexual', $profileData['orientacion_sexual'] ?? '')"
                            placeholder="Selecciona..."
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base"
                        />
                        @error('orientacion_sexual')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Busco y Ciudad en Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Busco (Preferencia de género) -->
                    <div>
                        <label for="busco" class="block text-sm font-semibold text-brown mb-2">Busco personas de género</label>
                        <x-dynamic-select
                            tipo="busco"
                            name="busco"
                            id="busco"
                            :required="true"
                            :selected="old('busco', $profileData['busco'] ?? '')"
                            placeholder="Selecciona..."
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base"
                        />
                        @error('busco')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ciudad -->
                    <div>
                        <label for="ciudad" class="block text-sm font-semibold text-brown mb-2">¿Dónde vives?</label>
                        <x-dynamic-select
                            tipo="ciudad"
                            name="ciudad"
                            id="ciudad"
                            :required="true"
                            :selected="old('ciudad', $profileData['ciudad'] ?? '')"
                            placeholder="Busca tu ciudad..."
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base"
                        />
                        @error('ciudad')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Biografía -->
                <div>
                    <label for="biografia" class="block text-sm font-semibold text-brown mb-2">Sobre mí</label>
                    <textarea id="biografia" name="biografia" rows="4"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base resize-none"
                        placeholder="Cuéntanos algo sobre ti, tus pasiones, qué te gusta hacer en Mallorca...">{{ old('biografia') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                    @error('biografia')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Intereses -->
                <div>
                    <label class="block text-sm font-semibold text-brown mb-3">Intereses</label>
                    <div id="intereses-container" class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-3">
                        @foreach(['playa', 'senderismo', 'gastronomía', 'deportes', 'música', 'arte', 'viajes', 'yoga', 'lectura', 'cine', 'fotografía', 'cocina'] as $interes)
                        <label class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 hover:bg-cream transition cursor-pointer">
                            <input type="checkbox" name="intereses[]" value="{{ $interes }}"
                                {{ in_array($interes, old('intereses', [])) ? 'checked' : '' }}
                                class="rounded text-heart-red focus:ring-heart-red">
                            <span class="text-sm">{{ ucfirst($interes) }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('intereses')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" class="w-full sm:flex-1 bg-heart-red text-white py-3 px-6 rounded-full hover:bg-red-700 transition font-semibold text-sm sm:text-base shadow-lg">
                        Crear Perfil
                    </button>
                    <a href="{{ route('dashboard') }}" class="w-full sm:flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-full hover:bg-gray-300 transition font-semibold text-center text-sm sm:text-base">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview-image');
    const container = document.getElementById('preview-container');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            container.querySelector('svg')?.classList.add('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar Select2 en el campo de ciudad
$(document).ready(function() {
    $('#ciudad').select2({
        placeholder: 'Busca tu ciudad...',
        allowClear: false,
        language: {
            noResults: function() {
                return "No se encontraron resultados";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
});
</script>
@endsection
