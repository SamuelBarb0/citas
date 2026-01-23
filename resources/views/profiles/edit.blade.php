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

                <!-- Foto de Perfil (Principal) -->
                <div class="text-center">
                    <label class="block text-sm font-semibold text-brown mb-3">Foto de Perfil</label>
                    <p class="text-xs text-gray-500 mb-4">Esta sera tu foto principal que veran los demas usuarios.</p>
                    <div class="flex flex-col items-center">
                        <div class="relative group">
                            <div id="preview-container" class="w-36 h-36 sm:w-44 sm:h-44 rounded-2xl bg-gray-200 flex items-center justify-center overflow-hidden mb-4 shadow-lg border-4 border-white">
                                @if($profile->foto_principal)
                                    <img id="preview-image" src="{{ str_starts_with($profile->foto_principal, 'http') ? $profile->foto_principal : Storage::url($profile->foto_principal) }}" alt="{{ $profile->nombre }}" class="w-full h-full object-cover">
                                @else
                                    <svg id="preview-placeholder" class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <img id="preview-image" src="" alt="Preview" class="hidden w-full h-full object-cover">
                                @endif
                            </div>
                            <!-- Badge Principal -->
                            <div class="absolute -top-2 -right-2 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 shadow-lg">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Principal
                            </div>
                        </div>
                        <label for="foto_principal" class="cursor-pointer bg-gradient-to-r from-brown to-heart-red text-white px-6 py-2.5 rounded-full hover:shadow-lg transition text-sm sm:text-base font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Cambiar Foto
                        </label>
                        <input type="file" id="foto_principal" name="foto_principal" accept="image/*" class="hidden" onchange="previewMainPhoto(event)">
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG (max. 2MB)</p>
                    </div>
                    @error('foto_principal')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Separador -->
                <div class="border-t border-gray-200 my-2"></div>

                <!-- Fotos Adicionales (Galeria) -->
                <div>
                    <label class="block text-sm font-semibold text-brown mb-2">Fotos Adicionales</label>
                    <p class="text-xs text-gray-500 mb-4">Anade hasta 6 fotos mas para tu galeria. Apareceran junto a tu foto principal.</p>

                    @php
                        $fotosAdicionales = $profile->fotos_adicionales ?? [];
                        if (!is_array($fotosAdicionales)) $fotosAdicionales = [];
                        $fotosAdicionales = array_slice($fotosAdicionales, 0, 6);
                    @endphp

                    <div class="grid grid-cols-3 sm:grid-cols-3 gap-3" id="additional-photos-grid">
                        <!-- Fotos adicionales existentes -->
                        @foreach($fotosAdicionales as $index => $foto)
                            <div class="additional-photo-item relative aspect-square rounded-xl overflow-hidden bg-gray-100 group" data-index="{{ $index }}" data-photo="{{ $foto }}">
                                <img src="{{ str_starts_with($foto, 'http') ? $foto : Storage::url($foto) }}"
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-full h-full object-cover">

                                <!-- Overlay con boton eliminar -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button"
                                            onclick="removeAdditionalPhoto({{ $index }})"
                                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-100 transition shadow-lg"
                                            title="Eliminar foto">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Numero de foto -->
                                <div class="absolute bottom-2 right-2 bg-black/60 text-white px-2 py-1 rounded-full text-[10px] font-bold">
                                    {{ $index + 1 }}
                                </div>

                                <!-- Input oculto -->
                                <input type="hidden" name="fotos_adicionales_existentes[]" value="{{ $foto }}">
                            </div>
                        @endforeach

                        <!-- Slots para nuevas fotos -->
                        @for($i = count($fotosAdicionales); $i < 6; $i++)
                            <label for="fotos_adicionales" class="add-photo-slot aspect-square rounded-xl border-2 border-dashed border-gray-300 hover:border-brown hover:bg-cream/50 transition cursor-pointer flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="text-xs text-gray-500 mt-1">Foto {{ $i + 1 }}</span>
                            </label>
                        @endfor
                    </div>

                    <!-- Input para nuevas fotos adicionales -->
                    <input type="file" id="fotos_adicionales" name="fotos_adicionales[]" accept="image/*" multiple class="hidden" onchange="handleAdditionalPhotos(event)">

                    <!-- Input oculto para fotos a eliminar -->
                    <input type="hidden" name="fotos_eliminar" id="fotos_eliminar" value="">

                    <p class="text-xs text-gray-500 mt-3">JPG, PNG (max. 2MB por foto). <span id="espacios-disponibles">{{ 6 - count($fotosAdicionales) }}</span> espacios disponibles.</p>

                    @error('fotos_adicionales.*')
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

                <!-- Género -->
                <div>
                    <label for="genero" class="block text-sm font-semibold text-brown mb-2">Identidad de Género</label>
                    <x-dynamic-select
                        tipo="genero"
                        name="genero"
                        id="genero"
                        :required="true"
                        :value="old('genero', $profile->genero)"
                        placeholder="Selecciona..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brown focus:border-transparent transition text-sm sm:text-base"
                    />
                    @error('genero')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                            :value="old('busco', $profile->busco)"
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
                            :value="old('ciudad', $profile->ciudad)"
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
                        placeholder="Cuéntanos algo sobre ti, tus pasiones, qué te gusta hacer en Mallorca...">{{ old('biografia', $profile->biografia) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                    @error('biografia')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Intereses -->
                <div>
                    <label class="block text-sm font-semibold text-brown mb-3">Intereses</label>
                    <div id="intereses-container" class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-3 max-h-80 overflow-y-auto pr-2">
                        @php
                            $interesesActuales = old('intereses', $profile->intereses ?? []);
                            $interesesDisponibles = [
                                'Tomar un café', 'Paseo tranquilo', 'Conversaciones picantes', 'Picnic en la playa',
                                'Series', 'Cine', 'Noche de peli en casa', 'Salir a comer o cenar', 'Cocinar',
                                'Tomar una copa', 'Yoga', 'Spa', 'Leer', 'Museos o exposiciones', 'Jardinería',
                                'Pintura y dibujos', 'Mercadillos', 'Gastronomía', 'Naturaleza', 'Astronomía',
                                'Conversaciones', 'Planes tranquis', 'Poesía', '¿Desayunamos?', 'Paseos con mascotas',
                                'Viajar', 'Senderismo', 'Paseo en bicicleta', 'Karting', 'Parques de atracciones',
                                'Escapadas de fin de semana', '¿Exploramos la isla?', '¿Vamos de cañas/tapas?', 'Tardeo',
                                'Fútbol', 'Pádel', 'Gimnasio', 'Natación', '¿Salimos a bailar?', 'Camping/autocaravana',
                                'Shopping', '¿Salimos de marcha?', 'Baloncesto', 'Quedar en grupo', 'Aventuras improvisadas',
                                'Aventuras planeadas', 'Juegos', 'Probar sitios nuevos', 'Fotografía', 'Aprender idiomas',
                                'Espiritualidad', 'Crecimiento personal', 'Autoconocimiento', 'Vida consciente', 'Numerología',
                                'Astrología', 'Personas PAS', 'Personas TDAH', 'MBTI', 'Profundidad', 'Voluntariado',
                                'Cuidado de mascotas', 'Monogamia', 'Relación abierta', 'Respeto', 'Empatía',
                                'Relación estable', 'Amistades', 'Charlas profundas', 'Conversaciones nocturnas',
                                'Solo disfrutar', 'Poco a poco', 'Conexión', 'Responsabilidad afectiva', 'Hablar cuando pueda',
                                'Hablar cada día', 'LGBTQ+ friendly', 'Detalles', 'Enamorarse', 'Citas'
                            ];
                        @endphp
                        @foreach($interesesDisponibles as $interes)
                        <label class="flex items-center space-x-2 p-2 rounded-lg border border-gray-200 hover:bg-cream transition cursor-pointer">
                            <input type="checkbox" name="intereses[]" value="{{ $interes }}"
                                {{ in_array($interes, $interesesActuales) ? 'checked' : '' }}
                                class="rounded text-heart-red focus:ring-heart-red flex-shrink-0">
                            <span class="text-xs">{{ $interes }}</span>
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
let fotosAEliminar = [];
let additionalPhotoCount = {{ count($fotosAdicionales ?? []) }};
const MAX_ADDITIONAL_PHOTOS = 6;

// Preview de foto principal
function previewMainPhoto(event) {
    const input = event.target;
    const preview = document.getElementById('preview-image');
    const placeholder = document.getElementById('preview-placeholder');

    if (input.files && input.files[0]) {
        // Validar tamano
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('La imagen supera los 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder?.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Eliminar foto adicional existente
function removeAdditionalPhoto(index) {
    const photoItem = document.querySelector(`.additional-photo-item[data-index="${index}"]`);
    if (!photoItem) return;

    const photoPath = photoItem.dataset.photo;
    if (photoPath) {
        fotosAEliminar.push(photoPath);
        document.getElementById('fotos_eliminar').value = JSON.stringify(fotosAEliminar);
    }

    // Reemplazar con un slot vacio
    const grid = document.getElementById('additional-photos-grid');
    const newSlot = document.createElement('label');
    newSlot.htmlFor = 'fotos_adicionales';
    newSlot.className = 'add-photo-slot aspect-square rounded-xl border-2 border-dashed border-gray-300 hover:border-brown hover:bg-cream/50 transition cursor-pointer flex flex-col items-center justify-center';
    newSlot.innerHTML = `
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        <span class="text-xs text-gray-500 mt-1">Anadir</span>
    `;

    photoItem.replaceWith(newSlot);
    additionalPhotoCount--;
    document.getElementById('espacios-disponibles').textContent = MAX_ADDITIONAL_PHOTOS - additionalPhotoCount;

    // Reindexar
    reindexAdditionalPhotos();
}

// Reindexar fotos adicionales
function reindexAdditionalPhotos() {
    document.querySelectorAll('.additional-photo-item').forEach((item, i) => {
        item.dataset.index = i;
        item.querySelector('button')?.setAttribute('onclick', `removeAdditionalPhoto(${i})`);
        const numBadge = item.querySelector('.absolute.bottom-2.right-2');
        if (numBadge) numBadge.textContent = i + 1;
    });
}

// Manejar nuevas fotos adicionales
function handleAdditionalPhotos(event) {
    const files = event.target.files;
    if (!files || files.length === 0) return;

    const grid = document.getElementById('additional-photos-grid');
    const availableSlots = MAX_ADDITIONAL_PHOTOS - additionalPhotoCount;

    if (files.length > availableSlots) {
        alert(`Solo puedes subir ${availableSlots} foto(s) mas. Maximo 6 fotos adicionales.`);
        event.target.value = '';
        return;
    }

    // Validar tamano de archivos
    for (let file of files) {
        if (file.size > 2 * 1024 * 1024) {
            alert(`El archivo "${file.name}" supera los 2MB.`);
            event.target.value = '';
            return;
        }
    }

    // Crear previews para las nuevas fotos
    Array.from(files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const newIndex = additionalPhotoCount;

            // Buscar el primer slot vacio y reemplazarlo
            const emptySlot = grid.querySelector('.add-photo-slot');
            if (!emptySlot) return;

            const photoDiv = document.createElement('div');
            photoDiv.className = 'additional-photo-item relative aspect-square rounded-xl overflow-hidden bg-gray-100 group';
            photoDiv.dataset.index = newIndex;
            photoDiv.dataset.photo = '';
            photoDiv.dataset.isNew = 'true';

            photoDiv.innerHTML = `
                <img src="${e.target.result}" alt="Nueva foto" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button" onclick="removeNewAdditionalPhoto(this)" class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-red-100 transition shadow-lg" title="Eliminar foto">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                <div class="absolute bottom-2 right-2 bg-black/60 text-white px-2 py-1 rounded-full text-[10px] font-bold">${newIndex + 1}</div>
                <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-[10px] font-bold">Nueva</div>
            `;

            emptySlot.replaceWith(photoDiv);
            additionalPhotoCount++;
            document.getElementById('espacios-disponibles').textContent = MAX_ADDITIONAL_PHOTOS - additionalPhotoCount;
        };
        reader.readAsDataURL(file);
    });
}

// Eliminar foto adicional nueva (que aun no se ha subido)
function removeNewAdditionalPhoto(btn) {
    const photoItem = btn.closest('.additional-photo-item');
    if (!photoItem) return;

    const grid = document.getElementById('additional-photos-grid');
    const newSlot = document.createElement('label');
    newSlot.htmlFor = 'fotos_adicionales';
    newSlot.className = 'add-photo-slot aspect-square rounded-xl border-2 border-dashed border-gray-300 hover:border-brown hover:bg-cream/50 transition cursor-pointer flex flex-col items-center justify-center';
    newSlot.innerHTML = `
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        <span class="text-xs text-gray-500 mt-1">Anadir</span>
    `;

    photoItem.replaceWith(newSlot);
    additionalPhotoCount--;
    document.getElementById('espacios-disponibles').textContent = MAX_ADDITIONAL_PHOTOS - additionalPhotoCount;

    // Limpiar el input de archivos
    document.getElementById('fotos_adicionales').value = '';

    reindexAdditionalPhotos();
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
