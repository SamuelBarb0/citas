<x-app-layout>
    <div class="fixed inset-0 bg-gradient-to-br from-cream via-white to-cream flex flex-col overflow-hidden">
        <!-- Header fijo -->
        <div class="flex-shrink-0 bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">👥 Ver Usuarios</h1>
                        <p class="text-white/90 mt-1 text-lg">Buscar, editar o suspender usuarios</p>
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
                    <form action="{{ route('admin.users') }}" method="GET" class="flex gap-3">
                        <div class="flex-1">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Buscar por nombre, email o ciudad..."
                                   class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                        </div>
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <button type="submit"
                                class="px-6 py-2 bg-brown text-white rounded-xl font-bold hover:bg-brown-dark transition">
                            🔍 Buscar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.users', request()->only('filter')) }}"
                               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
                                Limpiar
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Filtros rápidos -->
                <div class="mb-6 flex gap-3 flex-wrap">
                    <a href="{{ route('admin.users', request()->only('search')) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ !request('filter') ? 'bg-brown text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Todos ({{ \App\Models\User::count() }})
                    </a>
                    <a href="{{ route('admin.users', array_merge(request()->only('search'), ['filter' => 'active'])) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('filter') === 'active' ? 'bg-green-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Activos
                    </a>
                    <a href="{{ route('admin.users', array_merge(request()->only('search'), ['filter' => 'suspended'])) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('filter') === 'suspended' ? 'bg-red-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Suspendidos
                    </a>
                    <a href="{{ route('admin.users', array_merge(request()->only('search'), ['filter' => 'verified'])) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('filter') === 'verified' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Verificados
                    </a>
                </div>

                @if($users->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Perfil
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Estadísticas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Registro
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50 transition">
                                            <!-- Usuario -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($user->profile && $user->profile->foto_principal)
                                                        <img src="{{ $user->profile->foto_principal }}"
                                                             alt="{{ $user->name }}"
                                                             class="w-10 h-10 rounded-full object-cover">
                                                    @else
                                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="font-semibold text-brown">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                        <div class="text-xs text-gray-400">ID: {{ $user->id }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Perfil -->
                                            <td class="px-6 py-4">
                                                @if($user->profile)
                                                    <div>
                                                        <div class="font-semibold text-gray-900 flex items-center gap-1">
                                                            {{ $user->profile->nombre }}
                                                            @if($user->profile->verified)
                                                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $user->profile->edad }} años • {{ $user->profile->ciudad }}</div>
                                                        <div class="text-xs text-gray-400">{{ $user->profile->genero }} busca {{ $user->profile->busco }}</div>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">Sin perfil</span>
                                                @endif
                                            </td>

                                            <!-- Estado -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->profile)
                                                    @if($user->profile->activo)
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Suspendido
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Sin perfil
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Estadísticas -->
                                            <td class="px-6 py-4">
                                                <div class="space-y-1 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-gray-600">{{ $user->matches()->count() }} matches</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                        </svg>
                                                        <span class="text-gray-600">{{ $user->likes()->count() }} likes dados</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Registro -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->created_at->format('d/m/Y') }}<br>
                                                <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                                            </td>

                                            <!-- Acciones -->
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if($user->profile)
                                                        <a href="{{ route('profile.public', $user->id) }}"
                                                           target="_blank"
                                                           class="text-blue-600 hover:text-blue-900">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </a>

                                                        @if($user->profile->activo)
                                                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                        onclick="return confirm('¿Estás seguro de suspender a este usuario?')"
                                                                        class="text-red-600 hover:text-red-900">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="text-green-600 hover:text-green-900">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
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
                        {{ $users->links() }}
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="text-center py-16">
                        <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-brown mb-2">No hay usuarios</h3>
                        <p class="text-gray-500">No se encontraron usuarios con los filtros seleccionados.</p>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
