@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cream py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-brown mb-2">Editar mi Perfil</h1>
            <p class="text-gray-600 text-sm sm:text-base">Actualiza tu información</p>
        </div>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Foto de Perfil -->
                <div class="text-center">
                    <label class="block text-sm font-semibold text-brown mb-3">Foto de Perfil</label>
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div id="preview-container" class="w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-4">
                                @if($profile->foto_principal)
                                    <img id="preview-image" src="{{ str_starts_with($profile->foto_principal, 'http') ? $profile->foto_principal : Storage::url($profile->foto_principal) }}" alt="{{ $profile->nombre }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <img id="preview-image" src="" alt="Preview" class="hidden w-full h-full object-cover">
                                @endif
                            </div>
                        </div>
                        <label for="foto_principal" class="cursor-pointer bg-brown text-white px-6 py-2 rounded-full hover:bg-opacity-90 transition text-sm sm:text-base">
                            Cambiar Foto
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
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $profile->nombre) }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                    @error('nombre')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Edad y Género en Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Edad -->
                    <div>
                        <label for="edad" class="block text-sm font-semibold text-brown mb-2">Edad</label>
                        <input type="number" id="edad" name="edad" value="{{ old('edad', $profile->edad) }}" min="18" max="100" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                        @error('edad')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Género -->
                    <div>
                        <label for="genero" class="block text-sm font-semibold text-brown mb-2">Género</label>
                        <select id="genero" name="genero" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>
                            <option value="hombre" {{ old('genero', $profile->genero) == 'hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="mujer" {{ old('genero', $profile->genero) == 'mujer' ? 'selected' : '' }}>Mujer</option>
                            <option value="otro" {{ old('genero', $profile->genero) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('genero')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Busco y Ciudad en Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Busco -->
                    <div>
                        <label for="busco" class="block text-sm font-semibold text-brown mb-2">Busco</label>
                        <select id="busco" name="busco" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>
                            <option value="hombre" {{ old('busco', $profile->busco) == 'hombre' ? 'selected' : '' }}>Hombres</option>
                            <option value="mujer" {{ old('busco', $profile->busco) == 'mujer' ? 'selected' : '' }}>Mujeres</option>
                            <option value="ambos" {{ old('busco', $profile->busco) == 'ambos' ? 'selected' : '' }}>Ambos</option>
                        </select>
                        @error('busco')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ciudad -->
                    <div>
                        <label for="ciudad" class="block text-sm font-semibold text-brown mb-2">Ciudad</label>
                        <select id="ciudad" name="ciudad" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>
                            <option value="Palma de Mallorca" {{ old('ciudad', $profile->ciudad) == 'Palma de Mallorca' ? 'selected' : '' }}>Palma de Mallorca</option>
                            <option value="Alcúdia" {{ old('ciudad', $profile->ciudad) == 'Alcúdia' ? 'selected' : '' }}>Alcúdia</option>
                            <option value="Manacor" {{ old('ciudad', $profile->ciudad) == 'Manacor' ? 'selected' : '' }}>Manacor</option>
                            <option value="Inca" {{ old('ciudad', $profile->ciudad) == 'Inca' ? 'selected' : '' }}>Inca</option>
                            <option value="Sóller" {{ old('ciudad', $profile->ciudad) == 'Sóller' ? 'selected' : '' }}>Sóller</option>
                            <option value="Valldemossa" {{ old('ciudad', $profile->ciudad) == 'Valldemossa' ? 'selected' : '' }}>Valldemossa</option>
                            <option value="Pollença" {{ old('ciudad', $profile->ciudad) == 'Pollença' ? 'selected' : '' }}>Pollença</option>
                            <option value="Andratx" {{ old('ciudad', $profile->ciudad) == 'Andratx' ? 'selected' : '' }}>Andratx</option>
                            <option value="Otra" {{ old('ciudad', $profile->ciudad) == 'Otra' ? 'selected' : '' }}>Otra</option>
                        </select>
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
                        placeholder="Cuéntanos algo sobre ti, tus pasiones, qué te gusta hacer en Mallorca...">{{ old('biografia', $profile->biografia) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                    @error('biografia')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Intereses -->
                <div>
                    <label class="block text-sm font-semibold text-brown mb-3">Intereses</label>
                    <div id="intereses-container" class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-3">
                        @php
                            $interesesActuales = old('intereses', $profile->intereses ?? []);
                        @endphp
                        @foreach(['playa', 'senderismo', 'gastronomía', 'deportes', 'música', 'arte', 'viajes', 'yoga', 'lectura', 'cine', 'fotografía', 'cocina'] as $interes)
                        <label class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 hover:bg-cream transition cursor-pointer">
                            <input type="checkbox" name="intereses[]" value="{{ $interes }}"
                                {{ in_array($interes, $interesesActuales) ? 'checked' : '' }}
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
                        Guardar Cambios
                    </button>
                    <a href="{{ route('user.profile.show') }}" class="w-full sm:flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-full hover:bg-gray-300 transition font-semibold text-center text-sm sm:text-base">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

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
</script>
@endsection
