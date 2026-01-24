@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full mb-4 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-4xl font-black text-brown mb-3">Verifica tu perfil</h1>
            <p class="text-gray-600 text-lg">Aumenta tu credibilidad y recibe mas respuestas</p>
        </div>

        <!-- Beneficios destacados -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="font-bold text-brown text-sm">Mayor visibilidad</h3>
                <p class="text-gray-500 text-xs">Aparece primero en las busquedas</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="font-bold text-brown text-sm">Badge de confianza</h3>
                <p class="text-gray-500 text-xs">Distintivo azul en tu perfil</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 text-center">
                <div class="w-12 h-12 bg-heart-red/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-heart-red" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="font-bold text-brown text-sm">Mas matches</h3>
                <p class="text-gray-500 text-xs">Los usuarios confian mas en ti</p>
            </div>
        </div>

        <!-- Card principal -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
            <!-- Banner informativo -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100 p-6">
                <h2 class="text-xl font-bold text-blue-900 mb-3 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Como funciona la verificacion
                </h2>
                <ul class="space-y-2 text-blue-800">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Sube una <strong>selfie clara</strong> donde se vea tu rostro completo</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Haz un <strong>gesto con la mano</strong> (pulgar arriba, senal de paz, etc.)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Nuestro equipo revisara tu solicitud en <strong>24-48 horas</strong></span>
                    </li>
                </ul>
            </div>

            <!-- Formulario -->
            <div class="p-8">
                <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Preview de foto actual -->
                    <div class="text-center mb-6">
                        <p class="text-sm font-bold text-gray-700 mb-3">Tu foto de perfil actual:</p>
                        <img src="{{ $profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($profile->nombre) }}"
                             alt="{{ $profile->nombre }}"
                             class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-gray-200 shadow-lg">
                        <p class="text-xs text-gray-500 mt-2">La foto de verificacion debe ser similar para confirmar tu identidad</p>
                    </div>

                    <!-- Upload de foto -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Foto de verificacion <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input type="file"
                                   name="verification_photo"
                                   id="verification_photo"
                                   accept="image/jpeg,image/png,image/jpg"
                                   required
                                   class="hidden"
                                   onchange="previewImage(event)">

                            <label for="verification_photo"
                                   class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">
                                <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-16 h-16 mb-4 text-gray-400 group-hover:text-heart-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-bold">Haz clic para subir</span> o arrastra tu foto</p>
                                    <p class="text-xs text-gray-400">JPG, JPEG o PNG (Max. 5MB)</p>
                                </div>
                                <img id="image-preview" class="hidden w-full h-full object-contain rounded-2xl" alt="Vista previa">
                            </label>
                        </div>

                        @error('verification_photo')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tips -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <h3 class="font-bold text-yellow-900 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Requisitos para validar tu identidad:
                        </h3>
                        <ul class="text-sm text-yellow-800 space-y-1 ml-7">
                            <li>- Buena iluminacion (evita fotos oscuras o borrosas)</li>
                            <li>- Sin filtros ni efectos (necesitamos verificar que eres real)</li>
                            <li>- Sin gafas de sol (tus ojos deben ser visibles)</li>
                            <li>- Solo tu en la foto (sin otras personas)</li>
                        </ul>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('dashboard') }}"
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-4 px-6 rounded-full transition text-center">
                            Quiza mas tarde
                        </a>
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-heart-red to-heart-red-light hover:shadow-glow text-white font-bold py-4 px-6 rounded-full transition">
                            Enviar solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info adicional -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-gray-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-gray-600">
                    <p class="font-semibold text-gray-800 mb-1">Tu privacidad esta protegida</p>
                    <p>- Esta foto NO sera visible en tu perfil publico</p>
                    <p>- Solo nuestro equipo de moderacion la vera para validar tu identidad</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
