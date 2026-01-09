@extends('layouts.app')

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Estilos personalizados para Select2 */
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding: 0;
        color: #374151;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #A67C52;
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

    .select2-results__option--highlighted[aria-selected] {
        background-color: #A67C52 !important;
    }
</style>
@endpush

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

                <!-- Edad -->
                <div>
                    <label for="edad" class="block text-sm font-semibold text-brown mb-2">Edad</label>
                    <input type="number" id="edad" name="edad" value="{{ old('edad', $profile->edad) }}" min="18" max="100" required
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
                        <select id="genero" name="genero" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>
                            <option value="hombre" {{ old('genero', $profile->genero) == 'hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="mujer" {{ old('genero', $profile->genero) == 'mujer' ? 'selected' : '' }}>Mujer</option>
                            <option value="no-binario" {{ old('genero', $profile->genero) == 'no-binario' ? 'selected' : '' }}>No binario</option>
                            <option value="genero-fluido" {{ old('genero', $profile->genero) == 'genero-fluido' ? 'selected' : '' }}>Género fluido</option>
                            <option value="otro" {{ old('genero', $profile->genero) == 'otro' ? 'selected' : '' }}>Otro</option>
                            <option value="prefiero-no-decir" {{ old('genero', $profile->genero) == 'prefiero-no-decir' ? 'selected' : '' }}>Prefiero no decir</option>
                        </select>
                        @error('genero')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Orientación Sexual -->
                    <div>
                        <label for="orientacion_sexual" class="block text-sm font-semibold text-brown mb-2">Orientación Sexual</label>
                        <select id="orientacion_sexual" name="orientacion_sexual"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>
                            <option value="heterosexual" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'heterosexual' ? 'selected' : '' }}>Heterosexual</option>
                            <option value="gay" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'gay' ? 'selected' : '' }}>Gay</option>
                            <option value="lesbiana" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'lesbiana' ? 'selected' : '' }}>Lesbiana</option>
                            <option value="bisexual" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'bisexual' ? 'selected' : '' }}>Bisexual</option>
                            <option value="pansexual" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'pansexual' ? 'selected' : '' }}>Pansexual</option>
                            <option value="asexual" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'asexual' ? 'selected' : '' }}>Asexual</option>
                            <option value="queer" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'queer' ? 'selected' : '' }}>Queer</option>
                            <option value="otra" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'otra' ? 'selected' : '' }}>Otra</option>
                            <option value="prefiero-no-decir" {{ old('orientacion_sexual', $profile->orientacion_sexual) == 'prefiero-no-decir' ? 'selected' : '' }}>Prefiero no decir</option>
                        </select>
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
                        <select id="busco" name="busco" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>
                            <option value="hombre" {{ old('busco', $profile->busco) == 'hombre' ? 'selected' : '' }}>Hombres</option>
                            <option value="mujer" {{ old('busco', $profile->busco) == 'mujer' ? 'selected' : '' }}>Mujeres</option>
                            <option value="no-binario" {{ old('busco', $profile->busco) == 'no-binario' ? 'selected' : '' }}>No binario</option>
                            <option value="cualquiera" {{ old('busco', $profile->busco) == 'cualquiera' ? 'selected' : '' }}>Cualquier género</option>
                        </select>
                        @error('busco')
                            <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ciudad -->
                    <div>
                        <label for="ciudad" class="block text-sm font-semibold text-brown mb-2">¿Dónde vives?</label>
                        <select id="ciudad" name="ciudad" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base">
                            <option value="">Selecciona...</option>

                            <!-- Principales ciudades de Mallorca -->
                            <optgroup label="Principales Ciudades">
                                <option value="Palma de Mallorca" {{ old('ciudad', $profile->ciudad) == 'Palma de Mallorca' ? 'selected' : '' }}>Palma de Mallorca</option>
                                <option value="Manacor" {{ old('ciudad', $profile->ciudad) == 'Manacor' ? 'selected' : '' }}>Manacor</option>
                                <option value="Inca" {{ old('ciudad', $profile->ciudad) == 'Inca' ? 'selected' : '' }}>Inca</option>
                                <option value="Calvià" {{ old('ciudad', $profile->ciudad) == 'Calvià' ? 'selected' : '' }}>Calvià</option>
                                <option value="Llucmajor" {{ old('ciudad', $profile->ciudad) == 'Llucmajor' ? 'selected' : '' }}>Llucmajor</option>
                            </optgroup>

                            <!-- Todos los municipios de Mallorca (alfabéticamente) -->
                            <optgroup label="Todos los Municipios de Mallorca">
                                <option value="Alaró" {{ old('ciudad', $profile->ciudad) == 'Alaró' ? 'selected' : '' }}>Alaró</option>
                                <option value="Alcúdia" {{ old('ciudad', $profile->ciudad) == 'Alcúdia' ? 'selected' : '' }}>Alcúdia</option>
                                <option value="Algaida" {{ old('ciudad', $profile->ciudad) == 'Algaida' ? 'selected' : '' }}>Algaida</option>
                                <option value="Andratx" {{ old('ciudad', $profile->ciudad) == 'Andratx' ? 'selected' : '' }}>Andratx</option>
                                <option value="Ariany" {{ old('ciudad', $profile->ciudad) == 'Ariany' ? 'selected' : '' }}>Ariany</option>
                                <option value="Artà" {{ old('ciudad', $profile->ciudad) == 'Artà' ? 'selected' : '' }}>Artà</option>
                                <option value="Banyalbufar" {{ old('ciudad', $profile->ciudad) == 'Banyalbufar' ? 'selected' : '' }}>Banyalbufar</option>
                                <option value="Binissalem" {{ old('ciudad', $profile->ciudad) == 'Binissalem' ? 'selected' : '' }}>Binissalem</option>
                                <option value="Búger" {{ old('ciudad', $profile->ciudad) == 'Búger' ? 'selected' : '' }}>Búger</option>
                                <option value="Bunyola" {{ old('ciudad', $profile->ciudad) == 'Bunyola' ? 'selected' : '' }}>Bunyola</option>
                                <option value="Campanet" {{ old('ciudad', $profile->ciudad) == 'Campanet' ? 'selected' : '' }}>Campanet</option>
                                <option value="Campos" {{ old('ciudad', $profile->ciudad) == 'Campos' ? 'selected' : '' }}>Campos</option>
                                <option value="Capdepera" {{ old('ciudad', $profile->ciudad) == 'Capdepera' ? 'selected' : '' }}>Capdepera</option>
                                <option value="Consell" {{ old('ciudad', $profile->ciudad) == 'Consell' ? 'selected' : '' }}>Consell</option>
                                <option value="Costitx" {{ old('ciudad', $profile->ciudad) == 'Costitx' ? 'selected' : '' }}>Costitx</option>
                                <option value="Deià" {{ old('ciudad', $profile->ciudad) == 'Deià' ? 'selected' : '' }}>Deià</option>
                                <option value="Escorca" {{ old('ciudad', $profile->ciudad) == 'Escorca' ? 'selected' : '' }}>Escorca</option>
                                <option value="Esporles" {{ old('ciudad', $profile->ciudad) == 'Esporles' ? 'selected' : '' }}>Esporles</option>
                                <option value="Estellencs" {{ old('ciudad', $profile->ciudad) == 'Estellencs' ? 'selected' : '' }}>Estellencs</option>
                                <option value="Felanitx" {{ old('ciudad', $profile->ciudad) == 'Felanitx' ? 'selected' : '' }}>Felanitx</option>
                                <option value="Fornalutx" {{ old('ciudad', $profile->ciudad) == 'Fornalutx' ? 'selected' : '' }}>Fornalutx</option>
                                <option value="Lloret de Vistalegre" {{ old('ciudad', $profile->ciudad) == 'Lloret de Vistalegre' ? 'selected' : '' }}>Lloret de Vistalegre</option>
                                <option value="Lloseta" {{ old('ciudad', $profile->ciudad) == 'Lloseta' ? 'selected' : '' }}>Lloseta</option>
                                <option value="Magaluf" {{ old('ciudad', $profile->ciudad) == 'Magaluf' ? 'selected' : '' }}>Magaluf</option>
                                <option value="Manacor" {{ old('ciudad', $profile->ciudad) == 'Manacor' ? 'selected' : '' }}>Manacor</option>
                                <option value="Mancor de la Vall" {{ old('ciudad', $profile->ciudad) == 'Mancor de la Vall' ? 'selected' : '' }}>Mancor de la Vall</option>
                                <option value="Maria de la Salut" {{ old('ciudad', $profile->ciudad) == 'Maria de la Salut' ? 'selected' : '' }}>Maria de la Salut</option>
                                <option value="Marratxí" {{ old('ciudad', $profile->ciudad) == 'Marratxí' ? 'selected' : '' }}>Marratxí</option>
                                <option value="Montuïri" {{ old('ciudad', $profile->ciudad) == 'Montuïri' ? 'selected' : '' }}>Montuïri</option>
                                <option value="Muro" {{ old('ciudad', $profile->ciudad) == 'Muro' ? 'selected' : '' }}>Muro</option>
                                <option value="Petra" {{ old('ciudad', $profile->ciudad) == 'Petra' ? 'selected' : '' }}>Petra</option>
                                <option value="Pollença" {{ old('ciudad', $profile->ciudad) == 'Pollença' ? 'selected' : '' }}>Pollença</option>
                                <option value="Porreres" {{ old('ciudad', $profile->ciudad) == 'Porreres' ? 'selected' : '' }}>Porreres</option>
                                <option value="Puigpunyent" {{ old('ciudad', $profile->ciudad) == 'Puigpunyent' ? 'selected' : '' }}>Puigpunyent</option>
                                <option value="Sa Pobla" {{ old('ciudad', $profile->ciudad) == 'Sa Pobla' ? 'selected' : '' }}>Sa Pobla</option>
                                <option value="Sant Joan" {{ old('ciudad', $profile->ciudad) == 'Sant Joan' ? 'selected' : '' }}>Sant Joan</option>
                                <option value="Sant Llorenç des Cardassar" {{ old('ciudad', $profile->ciudad) == 'Sant Llorenç des Cardassar' ? 'selected' : '' }}>Sant Llorenç des Cardassar</option>
                                <option value="Santa Eugènia" {{ old('ciudad', $profile->ciudad) == 'Santa Eugènia' ? 'selected' : '' }}>Santa Eugènia</option>
                                <option value="Santa Margalida" {{ old('ciudad', $profile->ciudad) == 'Santa Margalida' ? 'selected' : '' }}>Santa Margalida</option>
                                <option value="Santa Maria del Camí" {{ old('ciudad', $profile->ciudad) == 'Santa Maria del Camí' ? 'selected' : '' }}>Santa Maria del Camí</option>
                                <option value="Santanyí" {{ old('ciudad', $profile->ciudad) == 'Santanyí' ? 'selected' : '' }}>Santanyí</option>
                                <option value="Selva" {{ old('ciudad', $profile->ciudad) == 'Selva' ? 'selected' : '' }}>Selva</option>
                                <option value="Sencelles" {{ old('ciudad', $profile->ciudad) == 'Sencelles' ? 'selected' : '' }}>Sencelles</option>
                                <option value="Ses Salines" {{ old('ciudad', $profile->ciudad) == 'Ses Salines' ? 'selected' : '' }}>Ses Salines</option>
                                <option value="Sineu" {{ old('ciudad', $profile->ciudad) == 'Sineu' ? 'selected' : '' }}>Sineu</option>
                                <option value="Sóller" {{ old('ciudad', $profile->ciudad) == 'Sóller' ? 'selected' : '' }}>Sóller</option>
                                <option value="Son Servera" {{ old('ciudad', $profile->ciudad) == 'Son Servera' ? 'selected' : '' }}>Son Servera</option>
                                <option value="Valldemossa" {{ old('ciudad', $profile->ciudad) == 'Valldemossa' ? 'selected' : '' }}>Valldemossa</option>
                                <option value="Vilafranca de Bonany" {{ old('ciudad', $profile->ciudad) == 'Vilafranca de Bonany' ? 'selected' : '' }}>Vilafranca de Bonany</option>
                            </optgroup>

                            <!-- Otras opciones -->
                            <optgroup label="Otras Opciones">
                                <option value="Otro pueblo de Mallorca" {{ old('ciudad', $profile->ciudad) == 'Otro pueblo de Mallorca' ? 'selected' : '' }}>Otro pueblo de Mallorca</option>
                                <option value="Otra isla (Menorca, Ibiza, Formentera)" {{ old('ciudad', $profile->ciudad) == 'Otra isla (Menorca, Ibiza, Formentera)' ? 'selected' : '' }}>Otra isla (Menorca, Ibiza, Formentera)</option>
                                <option value="Península (España)" {{ old('ciudad', $profile->ciudad) == 'Península (España)' ? 'selected' : '' }}>Península (España)</option>
                                <option value="Fuera de España" {{ old('ciudad', $profile->ciudad) == 'Fuera de España' ? 'selected' : '' }}>Fuera de España</option>
                            </optgroup>
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

<!-- jQuery (requerido para Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
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
