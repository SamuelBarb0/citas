<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">Cola de Verificación</h1>
                        <p class="text-white/80 mt-1">Aprobar o rechazar solicitudes de verificación</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-6 py-3 bg-white text-brown rounded-xl font-bold hover:shadow-lg transition">
                        Volver al Panel
                    </a>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
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

                @if($profiles->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Perfil
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Ubicación
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Estadísticas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Registrado
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($profiles as $profile)
                                        <tr class="hover:bg-gray-50 transition">
                                            <!-- Perfil -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($profile->foto_principal)
                                                        <img src="{{ $profile->foto_principal }}"
                                                             alt="{{ $profile->nombre }}"
                                                             class="w-12 h-12 rounded-full object-cover">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="font-semibold text-brown text-lg">{{ $profile->nombre }}</div>
                                                        <div class="text-sm text-gray-500">{{ $profile->edad }} años • {{ $profile->genero }}</div>
                                                        @if($profile->fotos_adicionales && count($profile->fotos_adicionales) > 0)
                                                            <div class="text-xs text-gray-400 mt-1">
                                                                📸 {{ count($profile->fotos_adicionales) + 1 }} fotos
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Usuario -->
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $profile->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $profile->user->email }}</div>
                                            </td>

                                            <!-- Ubicación -->
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">📍 {{ $profile->ciudad }}</div>
                                                @if($profile->intereses && count($profile->intereses) > 0)
                                                    <div class="flex flex-wrap gap-1 mt-2">
                                                        @foreach(array_slice($profile->intereses, 0, 2) as $interes)
                                                            <span class="px-2 py-1 bg-cream text-brown rounded-full text-xs font-semibold">
                                                                {{ $interes }}
                                                            </span>
                                                        @endforeach
                                                        @if(count($profile->intereses) > 2)
                                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                                                +{{ count($profile->intereses) - 2 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Estadísticas -->
                                            <td class="px-6 py-4">
                                                <div class="space-y-1 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-gray-600">{{ $profile->user->matches()->count() }} matches</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                        </svg>
                                                        <span class="text-gray-600">{{ $profile->user->likes()->count() }} likes</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                        </svg>
                                                        <span class="text-gray-600">{{ $profile->likedBy()->count() }} recibidos</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Registrado -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $profile->user->created_at->format('d/m/Y') }}<br>
                                                <span class="text-xs text-gray-400">{{ $profile->user->created_at->diffForHumans() }}</span>
                                            </td>

                                            <!-- Acciones -->
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('profile.public', $profile->user_id) }}"
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-900"
                                                       title="Ver perfil">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('admin.verify', $profile->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-green-600 hover:text-green-900"
                                                                title="Verificar perfil">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $profiles->links() }}
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
</x-app-layout>
