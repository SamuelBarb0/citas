<x-app-layout>
    <div class="fixed inset-0 bg-gradient-to-br from-cream via-white to-cream flex flex-col overflow-hidden">
        <!-- Header fijo -->
        <div class="flex-shrink-0 bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">✅ Verificar Perfiles</h1>
                        <p class="text-white/90 mt-1 text-lg">Aprobar o rechazar las solicitudes de verificación</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-6 py-3 bg-white text-brown rounded-xl font-bold hover:shadow-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Panel
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido con scroll -->
        <div class="flex-1 overflow-y-auto py-8 px-4 sm:px-6 lg:px-8" style="padding-bottom: 5rem;">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-6 py-4 rounded-2xl">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Buscador -->
                <div class="mb-6 bg-white rounded-2xl shadow-lg p-6">
                    <form action="{{ route('admin.verification') }}" method="GET" class="flex gap-3">
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Buscar por nombre, email o ciudad..."
                                   class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                        </div>
                        <button type="submit"
                                class="px-6 py-2 bg-brown text-white rounded-xl font-bold hover:bg-brown-dark transition">
                            🔍 Buscar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.verification') }}"
                               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
                                Limpiar
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Información -->
                <div class="mb-6 bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
                    <div class="flex gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-bold text-blue-900 mb-2">Sobre la verificación</h4>
                            <p class="text-sm text-blue-700">Los perfiles verificados obtienen una insignia azul que aumenta la confianza de otros usuarios. Revisa cuidadosamente cada perfil antes de verificarlo.</p>
                        </div>
                    </div>
                </div>

                @if($verificationRequests->count() > 0)
                    <!-- Grid de solicitudes -->
                    <div class="grid gap-6">
                        @foreach($verificationRequests as $request)
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-gray-100">
                                <div class="grid md:grid-cols-2 gap-6 p-6">
                                    <!-- Lado izquierdo: Info del usuario y foto de perfil -->
                                    <div>
                                        <div class="flex items-center gap-4 mb-4">
                                            <img src="{{ $request->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->profile->nombre) }}"
                                                 alt="{{ $request->profile->nombre }}"
                                                 class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                                            <div>
                                                <h3 class="text-xl font-black text-brown">{{ $request->profile->nombre }}</h3>
                                                <p class="text-gray-600">{{ $request->profile->edad }} años • {{ $request->profile->genero }}</p>
                                                <p class="text-sm text-gray-500">📍 {{ $request->profile->ciudad }}</p>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                            <h4 class="font-bold text-gray-700 mb-2">Información del usuario</h4>
                                            <div class="space-y-1 text-sm">
                                                <p class="text-gray-600"><strong>Email:</strong> {{ $request->user->email }}</p>
                                                <p class="text-gray-600"><strong>Registrado:</strong> {{ $request->user->created_at->format('d/m/Y') }} ({{ $request->user->created_at->diffForHumans() }})</p>
                                                <p class="text-gray-600"><strong>Solicitud enviada:</strong> {{ $request->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>

                                        <div class="bg-blue-50 rounded-xl p-4">
                                            <h4 class="font-bold text-blue-900 mb-2">Foto actual del perfil</h4>
                                            <img src="{{ $request->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->profile->nombre) }}"
                                                 alt="Foto de perfil actual"
                                                 class="w-full h-80 object-cover rounded-lg border-2 border-blue-200 cursor-pointer hover:opacity-90 transition"
                                                 onclick="openImageModal(this.src)">
                                            <p class="text-xs text-blue-700 mt-2 text-center">Click para ampliar</p>
                                        </div>
                                    </div>

                                    <!-- Lado derecho: Foto de verificación y acciones -->
                                    <div>
                                        <div class="bg-yellow-50 rounded-xl p-4 mb-4">
                                            <h4 class="font-bold text-yellow-900 mb-2">Foto de verificación (selfie con gesto)</h4>
                                            <img src="{{ asset('storage/' . $request->verification_photo) }}"
                                                 alt="Foto de verificación"
                                                 class="w-full h-80 object-cover rounded-lg border-2 border-yellow-200 cursor-pointer hover:opacity-90 transition"
                                                 onclick="openImageModal(this.src)">
                                            <p class="text-xs text-yellow-700 mt-2 text-center">Click para ampliar</p>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="space-y-3">
                                            <a href="{{ route('profile.public', $request->user_id) }}"
                                               target="_blank"
                                               class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver perfil completo
                                            </a>

                                            <form action="{{ route('admin.verify', $request->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:shadow-lg text-white rounded-xl font-bold transition"
                                                        onclick="return confirm('¿Estás seguro de que quieres aprobar esta verificación?')">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    ✓ Aprobar verificación
                                                </button>
                                            </form>

                                            <button type="button"
                                                    onclick="openRejectModal({{ $request->id }}, '{{ $request->profile->nombre }}')"
                                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                ✗ Rechazar solicitud
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $verificationRequests->links() }}
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="text-center py-16">
                        <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-16 h-16 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-brown mb-2">No hay perfiles pendientes</h3>
                        <p class="text-gray-500 mb-6">
                            @if(request('search'))
                                No se encontraron perfiles que coincidan con tu búsqueda.
                            @else
                                Todos los perfiles activos ya están verificados o no hay solicitudes pendientes.
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('admin.verification') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-brown text-white rounded-full font-bold hover:bg-brown-dark transition">
                                Ver todos los perfiles
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal para ampliar imágenes -->
    <div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/90" onclick="closeImageModal()">
        <div class="relative max-w-6xl max-h-screen p-4">
            <button onclick="closeImageModal()"
                    class="absolute top-6 right-6 text-white hover:text-gray-300 transition">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Imagen ampliada" class="max-w-full max-h-screen object-contain rounded-lg">
        </div>
    </div>

    <!-- Modal para rechazar verificación -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-brown">Rechazar verificación</h3>
            </div>

            <p class="text-gray-600 mb-4">
                ¿Estás seguro de que quieres rechazar la verificación de <strong id="rejectUserName"></strong>?
            </p>

            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Motivo del rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_notes"
                              required
                              rows="4"
                              class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown"
                              placeholder="Explica por qué se rechaza la verificación (ej: Foto borrosa, sin gesto visible, etc.)"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Este mensaje será enviado al usuario</p>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                            onclick="closeRejectModal()"
                            class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold transition">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition">
                        Rechazar solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function openRejectModal(requestId, userName) {
            document.getElementById('rejectUserName').textContent = userName;
            document.getElementById('rejectForm').action = '/admin/verification/' + requestId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Cerrar modales con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                closeRejectModal();
            }
        });
    </script>
        </div>
    </div>
</x-app-layout>
