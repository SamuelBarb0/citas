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

                <!-- Galería de Fotos -->
                <div>
                    <label class="block text-sm font-semibold text-brown mb-3">Mis Fotos</label>
                    <p class="text-xs text-gray-500 mb-4">Sube hasta 7 fotos. La foto con la estrella sera tu foto principal.</p>

                    @php
                        $fotosActuales = [];
                        if ($profile->foto_principal) {
                            $fotosActuales[] = $profile->foto_principal;
                        }
                        if ($profile->fotos_adicionales && is_array($profile->fotos_adicionales)) {
                            $fotosActuales = array_merge($fotosActuales, $profile->fotos_adicionales);
                        }
                        $fotosActuales = array_slice(array_unique($fotosActuales), 0, 7);
                    @endphp

                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3" id="photos-grid">
                        <!-- Fotos existentes -->
                        @foreach($fotosActuales as $index => $foto)
                            <div class="photo-item relative aspect-square rounded-xl overflow-hidden bg-gray-100 group" data-index="{{ $index }}" data-photo="{{ $foto }}">
                                <img src="{{ str_starts_with($foto, 'http') ? $foto : Storage::url($foto) }}"
                                     alt="Foto {{ $index + 1 }}"
                                     class="w-full h-full object-cover">

                                <!-- Overlay con acciones -->
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <!-- Boton establecer como principal -->
                                    <button type="button"
                                            onclick="setAsMain({{ $index }})"
                                            class="set-main-btn w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-yellow-100 transition {{ $index === 0 ? 'ring-2 ring-yellow-400' : '' }}"
                                            title="Establecer como principal">
                                        <svg class="w-4 h-4 {{ $index === 0 ? 'text-yellow-500' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    <!-- Boton eliminar -->
                                    <button type="button"
                                            onclick="removePhoto({{ $index }})"
                                            class="w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-red-100 transition"
                                            title="Eliminar foto">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Badge de foto principal -->
                                <div class="main-badge absolute top-2 left-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-[10px] font-bold items-center gap-1 shadow-lg {{ $index === 0 ? 'flex' : 'hidden' }}">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Principal
                                </div>

                                <!-- Input oculto para mantener el orden -->
                                <input type="hidden" name="fotos_orden[]" value="{{ $foto }}" class="foto-orden-input">
                            </div>
                        @endforeach

                        <!-- Slot para anadir nuevas fotos (si hay espacio) -->
                        @if(count($fotosActuales) < 7)
                            <label for="nuevas_fotos" class="add-photo-slot aspect-square rounded-xl border-2 border-dashed border-gray-300 hover:border-brown hover:bg-cream/50 transition cursor-pointer flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="text-xs text-gray-500 mt-1">Anadir</span>
                            </label>
                        @endif
                    </div>

                    <!-- Input para nuevas fotos -->
                    <input type="file" id="nuevas_fotos" name="nuevas_fotos[]" accept="image/*" multiple class="hidden" onchange="handleNewPhotos(event)">

                    <!-- Input oculto para fotos a eliminar -->
                    <input type="hidden" name="fotos_eliminar" id="fotos_eliminar" value="">

                    <!-- Input oculto para indice de foto principal -->
                    <input type="hidden" name="foto_principal_index" id="foto_principal_index" value="0">

                    <p class="text-xs text-gray-500 mt-3">JPG, PNG (max. 2MB por foto). <span id="espacios-disponibles">{{ 7 - count($fotosActuales) }}</span> espacios disponibles.</p>

                    @error('nuevas_fotos.*')
                        <p class="text-heart-red text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('fotos_orden')
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
let mainPhotoIndex = 0;
let photoCount = {{ count($fotosActuales) }};
const MAX_PHOTOS = 7;

// Establecer foto como principal
function setAsMain(index) {
    mainPhotoIndex = index;
    document.getElementById('foto_principal_index').value = index;

    // Actualizar badges visuales
    document.querySelectorAll('.photo-item').forEach((item, i) => {
        const badge = item.querySelector('.main-badge');
        const starBtn = item.querySelector('.set-main-btn');
        const starSvg = starBtn?.querySelector('svg');

        if (parseInt(item.dataset.index) === index) {
            badge?.classList.remove('hidden');
            badge?.classList.add('flex');
            starBtn?.classList.add('ring-2', 'ring-yellow-400');
            starSvg?.classList.remove('text-gray-600');
            starSvg?.classList.add('text-yellow-500');
        } else {
            badge?.classList.add('hidden');
            badge?.classList.remove('flex');
            starBtn?.classList.remove('ring-2', 'ring-yellow-400');
            starSvg?.classList.add('text-gray-600');
            starSvg?.classList.remove('text-yellow-500');
        }
    });
}

// Eliminar foto
function removePhoto(index) {
    const photoItem = document.querySelector(`.photo-item[data-index="${index}"]`);
    if (!photoItem) return;

    const photoPath = photoItem.dataset.photo;
    if (photoPath) {
        fotosAEliminar.push(photoPath);
        document.getElementById('fotos_eliminar').value = JSON.stringify(fotosAEliminar);
    }

    // Remover el elemento del DOM
    photoItem.remove();
    photoCount--;

    // Actualizar espacios disponibles
    document.getElementById('espacios-disponibles').textContent = MAX_PHOTOS - photoCount;

    // Reindexar las fotos restantes
    reindexPhotos();

    // Mostrar el boton de anadir si hay espacio
    updateAddPhotoSlot();

    // Si eliminamos la foto principal, la primera pasa a ser la principal
    if (index === mainPhotoIndex) {
        const firstPhoto = document.querySelector('.photo-item');
        if (firstPhoto) {
            setAsMain(0);
        }
    } else if (index < mainPhotoIndex) {
        mainPhotoIndex--;
        document.getElementById('foto_principal_index').value = mainPhotoIndex;
    }
}

// Reindexar fotos despues de eliminar
function reindexPhotos() {
    document.querySelectorAll('.photo-item').forEach((item, i) => {
        item.dataset.index = i;
        item.querySelector('.set-main-btn')?.setAttribute('onclick', `setAsMain(${i})`);
        item.querySelector('button[title="Eliminar foto"]')?.setAttribute('onclick', `removePhoto(${i})`);
    });
}

// Manejar nuevas fotos
function handleNewPhotos(event) {
    const files = event.target.files;
    if (!files || files.length === 0) return;

    const grid = document.getElementById('photos-grid');
    const availableSlots = MAX_PHOTOS - photoCount;

    if (files.length > availableSlots) {
        alert(`Solo puedes subir ${availableSlots} foto(s) mas. Maximo 7 fotos en total.`);
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
            const newIndex = photoCount;
            const photoDiv = document.createElement('div');
            photoDiv.className = 'photo-item relative aspect-square rounded-xl overflow-hidden bg-gray-100 group';
            photoDiv.dataset.index = newIndex;
            photoDiv.dataset.photo = '';
            photoDiv.dataset.isNew = 'true';

            photoDiv.innerHTML = `
                <img src="${e.target.result}" alt="Nueva foto" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button type="button" onclick="setAsMain(${newIndex})" class="set-main-btn w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-yellow-100 transition" title="Establecer como principal">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                    <button type="button" onclick="removeNewPhoto(this)" class="w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-red-100 transition" title="Eliminar foto">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                <div class="main-badge absolute top-2 left-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-[10px] font-bold items-center gap-1 shadow-lg hidden">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Principal
                </div>
                <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-[10px] font-bold">Nueva</div>
            `;

            // Insertar antes del boton de anadir
            const addSlot = grid.querySelector('.add-photo-slot');
            if (addSlot) {
                grid.insertBefore(photoDiv, addSlot);
            } else {
                grid.appendChild(photoDiv);
            }

            photoCount++;
            document.getElementById('espacios-disponibles').textContent = MAX_PHOTOS - photoCount;
            updateAddPhotoSlot();
        };
        reader.readAsDataURL(file);
    });
}

// Eliminar foto nueva (que aun no se ha subido)
function removeNewPhoto(btn) {
    const photoItem = btn.closest('.photo-item');
    if (!photoItem) return;

    photoItem.remove();
    photoCount--;
    document.getElementById('espacios-disponibles').textContent = MAX_PHOTOS - photoCount;
    reindexPhotos();
    updateAddPhotoSlot();

    // Limpiar el input de archivos para permitir volver a seleccionar
    document.getElementById('nuevas_fotos').value = '';
}

// Actualizar visibilidad del boton de anadir fotos
function updateAddPhotoSlot() {
    const grid = document.getElementById('photos-grid');
    let addSlot = grid.querySelector('.add-photo-slot');

    if (photoCount < MAX_PHOTOS) {
        if (!addSlot) {
            addSlot = document.createElement('label');
            addSlot.htmlFor = 'nuevas_fotos';
            addSlot.className = 'add-photo-slot aspect-square rounded-xl border-2 border-dashed border-gray-300 hover:border-brown hover:bg-cream/50 transition cursor-pointer flex flex-col items-center justify-center';
            addSlot.innerHTML = `
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="text-xs text-gray-500 mt-1">Anadir</span>
            `;
            grid.appendChild(addSlot);
        }
    } else {
        addSlot?.remove();
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
