<x-app-layout>
    <div class="fixed inset-0 bg-gradient-to-br from-cream via-white to-cream flex flex-col overflow-hidden">
        <!-- Header fijo -->
        <div class="flex-shrink-0 bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">Editar Usuario</h1>
                        <p class="text-white/90 mt-1 text-lg">{{ $user->name }} ({{ $user->email }})</p>
                    </div>
                    <a href="{{ route('admin.users') }}"
                       class="px-6 py-3 bg-white text-brown rounded-xl font-bold hover:shadow-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido con scroll -->
        <div class="flex-1 overflow-y-auto py-8 px-4 sm:px-6 lg:px-8" style="padding-bottom: 5rem;">
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-6 py-4 rounded-2xl">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-2 border-red-200 text-red-800 px-6 py-4 rounded-2xl">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Datos de cuenta -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-black text-brown mb-4">Datos de Cuenta</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_admin" value="1"
                                       {{ $user->is_admin ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-brown focus:ring-brown">
                                <span class="text-sm font-semibold text-gray-700">Es administrador</span>
                            </label>
                        </div>

                        <div class="mt-4 text-sm text-gray-500">
                            <span>Registrado: {{ $user->created_at->format('d/m/Y H:i') }}</span>
                            <span class="ml-4">ID: {{ $user->id }}</span>
                        </div>
                    </div>

                    <!-- Datos de perfil -->
                    @if($user->profile)
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-black text-brown mb-4">Datos de Perfil</h2>

                        <div class="flex items-center gap-4 mb-6">
                            @if($user->profile->foto_principal)
                                <img src="{{ str_starts_with($user->profile->foto_principal, 'http') ? $user->profile->foto_principal : Storage::url($user->profile->foto_principal) }}"
                                     alt="{{ $user->profile->nombre }}"
                                     class="w-20 h-20 rounded-full object-cover">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-brown text-lg">{{ $user->profile->nombre }}</p>
                                <p class="text-sm text-gray-500">{{ $user->profile->edad }} anos - {{ $user->profile->genero }}</p>
                                <p class="text-xs text-gray-400">
                                    Estado:
                                    @if($user->profile->activo)
                                        <span class="text-green-600 font-semibold">Activo</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Suspendido</span>
                                    @endif
                                    @if($user->profile->verified)
                                        | <span class="text-blue-600 font-semibold">Verificado</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de perfil</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $user->profile->nombre) }}"
                                       class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Ciudad</label>
                                <input type="text" name="ciudad" value="{{ old('ciudad', $user->profile->ciudad) }}"
                                       class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Biografia</label>
                            <textarea name="biografia" rows="3"
                                      class="w-full rounded-xl border-gray-300 focus:ring-brown focus:border-brown">{{ old('biografia', $user->profile->biografia) }}</textarea>
                        </div>

                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <p class="font-bold text-brown text-lg">{{ $user->matches()->count() }}</p>
                                <p>Matches</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <p class="font-bold text-brown text-lg">{{ $user->likes()->count() }}</p>
                                <p>Likes dados</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <p class="font-bold text-brown text-lg">{{ \App\Models\Like::where('liked_user_id', $user->id)->count() }}</p>
                                <p>Likes recibidos</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <p class="font-bold text-brown text-lg">{{ \App\Models\Report::where('reported_user_id', $user->id)->count() }}</p>
                                <p>Reportes</p>
                            </div>
                        </div>
                    </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 text-center text-gray-500">
                            Este usuario no tiene perfil creado.
                        </div>
                    @endif

                    <!-- Botones -->
                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-brown text-white rounded-xl font-bold hover:bg-brown-dark transition text-center">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('admin.users') }}"
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition text-center">
                            Cancelar
                        </a>
                    </div>
                </form>

                <!-- Acciones peligrosas -->
                @if(!$user->is_admin)
                <div class="mt-8 bg-red-50 rounded-2xl shadow-lg p-6 border-2 border-red-200">
                    <h2 class="text-xl font-black text-red-700 mb-4">Zona de peligro</h2>
                    <div class="flex gap-3 flex-wrap">
                        @if($user->profile && $user->profile->activo)
                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('¿Suspender a {{ $user->name }}?')"
                                        class="px-4 py-2 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition text-sm">
                                    Suspender Usuario
                                </button>
                            </form>
                        @elseif($user->profile)
                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 bg-green-500 text-white rounded-xl font-bold hover:bg-green-600 transition text-sm">
                                    Reactivar Usuario
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('¿ELIMINAR PERMANENTEMENTE a {{ $user->name }}? Todos sus datos, fotos, matches y mensajes seran eliminados. Esta accion NO se puede deshacer.')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition text-sm">
                                Eliminar Usuario Permanentemente
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
