<x-app-layout>
    <div class="fixed inset-0 bg-gradient-to-br from-cream via-white to-cream flex flex-col overflow-hidden">
        <!-- Header fijo -->
        <div class="flex-shrink-0 bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">⚠️ Reportes de Usuarios</h1>
                        <p class="text-white/90 mt-1 text-lg">Ver y resolver quejas o problemas reportados</p>
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

                <!-- Filtros por estado -->
                <div class="mb-6 flex gap-3 flex-wrap">
                    <a href="{{ route('admin.reports') }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ !request('status') ? 'bg-brown text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Todos ({{ $reports->total() }})
                    </a>
                    <a href="{{ route('admin.reports', ['status' => 'pendiente']) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('status') === 'pendiente' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Pendientes
                    </a>
                    <a href="{{ route('admin.reports', ['status' => 'revisado']) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('status') === 'revisado' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Revisados
                    </a>
                    <a href="{{ route('admin.reports', ['status' => 'accion_tomada']) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('status') === 'accion_tomada' ? 'bg-green-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Acción Tomada
                    </a>
                    <a href="{{ route('admin.reports', ['status' => 'descartado']) }}"
                       class="px-4 py-2 rounded-xl font-semibold transition
                              {{ request('status') === 'descartado' ? 'bg-gray-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                        Descartados
                    </a>
                </div>

                @if($reports->count() > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($reports as $report)
                            @php
                                $statusColors = [
                                    'pendiente' => 'border-yellow-500 bg-yellow-50',
                                    'revisado' => 'border-blue-500 bg-blue-50',
                                    'accion_tomada' => 'border-green-500 bg-green-50',
                                    'descartado' => 'border-gray-500 bg-gray-50',
                                ];
                                $statusText = [
                                    'pendiente' => 'Pendiente',
                                    'revisado' => 'Revisado',
                                    'accion_tomada' => 'Acción Tomada',
                                    'descartado' => 'Descartado',
                                ];
                            @endphp
                            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-l-4 {{ $statusColors[$report->status] ?? 'border-gray-500 bg-gray-50' }}">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="px-3 py-1 rounded-full text-sm font-bold
                                                    {{ $report->status === 'pendiente' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                                    {{ $report->status === 'revisado' ? 'bg-blue-200 text-blue-800' : '' }}
                                                    {{ $report->status === 'accion_tomada' ? 'bg-green-200 text-green-800' : '' }}
                                                    {{ $report->status === 'descartado' ? 'bg-gray-200 text-gray-800' : '' }}">
                                                    {{ $statusText[$report->status] ?? $report->status }}
                                                </span>
                                                <span class="text-sm text-gray-500">Reporte #{{ $report->id }}</span>
                                            </div>
                                            <p class="text-xs text-gray-400">Reportado {{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <!-- Reportador -->
                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Reportado por:</h4>
                                            <div class="flex items-center gap-3">
                                                @if($report->reporter && $report->reporter->profile && $report->reporter->profile->foto_principal)
                                                    <img src="{{ $report->reporter->profile->foto_principal }}"
                                                         alt="{{ $report->reporter->profile->nombre }}"
                                                         class="w-12 h-12 rounded-full object-cover">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-brown">
                                                        {{ $report->reporter && $report->reporter->profile ? $report->reporter->profile->nombre : 'Usuario eliminado' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">ID: {{ $report->reporter_id }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Usuario Reportado -->
                                        <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                                            <h4 class="text-xs font-semibold text-red-700 uppercase mb-3">Usuario Reportado:</h4>
                                            <div class="flex items-center gap-3">
                                                @if($report->reportedUser && $report->reportedUser->profile && $report->reportedUser->profile->foto_principal)
                                                    <img src="{{ $report->reportedUser->profile->foto_principal }}"
                                                         alt="{{ $report->reportedUser->profile->nombre }}"
                                                         class="w-12 h-12 rounded-full object-cover">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-bold text-brown">
                                                        {{ $report->reportedUser && $report->reportedUser->profile ? $report->reportedUser->profile->nombre : 'Usuario eliminado' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">ID: {{ $report->reported_user_id }}</p>
                                                    @if($report->reportedUser)
                                                        <a href="{{ route('profile.public', $report->reported_user_id) }}"
                                                           target="_blank"
                                                           class="text-xs text-blue-600 hover:underline">
                                                            Ver perfil →
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Razón del reporte -->
                                    <div class="mb-6">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Motivo del reporte:</h4>
                                        <div class="bg-gray-50 rounded-xl p-4">
                                            <p class="font-semibold text-brown mb-1">
                                                @php
                                                    $reasonLabels = [
                                                        'inapropiado' => 'Contenido inapropiado',
                                                        'spam' => 'Spam o publicidad',
                                                        'acoso' => 'Acoso o comportamiento abusivo',
                                                        'fake' => 'Perfil falso o engañoso',
                                                        'menor' => 'Menor de edad',
                                                        'otro' => 'Otro motivo',
                                                    ];
                                                @endphp
                                                {{ $reasonLabels[$report->reason] ?? $report->reason }}
                                            </p>
                                            @if($report->details)
                                                <p class="text-sm text-gray-600 mt-2">{{ $report->details }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Cambiar estado -->
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Cambiar estado:</h4>
                                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <select name="status"
                                                    class="flex-1 rounded-xl border-gray-300 focus:ring-brown focus:border-brown">
                                                <option value="pendiente" {{ $report->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="revisado" {{ $report->status === 'revisado' ? 'selected' : '' }}>Revisado</option>
                                                <option value="accion_tomada" {{ $report->status === 'accion_tomada' ? 'selected' : '' }}>Acción Tomada</option>
                                                <option value="descartado" {{ $report->status === 'descartado' ? 'selected' : '' }}>Descartado</option>
                                            </select>
                                            <button type="submit"
                                                    class="px-6 py-2 bg-brown text-white rounded-xl font-bold hover:bg-brown-dark transition">
                                                Actualizar
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Acciones sobre el usuario reportado -->
                                    @if($report->reportedUser && !$report->reportedUser->is_admin)
                                    <div class="border-t pt-4 mt-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Acciones sobre el usuario reportado:</h4>
                                        <div class="flex gap-2 flex-wrap">
                                            {{-- Suspender --}}
                                            @if($report->reportedUser->profile && $report->reportedUser->profile->activo)
                                                <form action="{{ route('admin.users.suspend', $report->reported_user_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            onclick="return confirm('¿Suspender a {{ $report->reportedUser->profile->nombre ?? $report->reportedUser->name }}?')"
                                                            class="px-4 py-2 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition text-sm flex items-center gap-1.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                        Suspender
                                                    </button>
                                                </form>
                                            @elseif($report->reportedUser->profile)
                                                <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-xl font-bold text-sm">Ya suspendido</span>
                                            @endif

                                            {{-- Eliminar --}}
                                            <form action="{{ route('admin.users.delete', $report->reported_user_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('¿ELIMINAR PERMANENTEMENTE a {{ $report->reportedUser->profile->nombre ?? $report->reportedUser->name }}? Todos sus datos seran eliminados. Esta accion NO se puede deshacer.')"
                                                        class="px-4 py-2 bg-red-700 text-white rounded-xl font-bold hover:bg-red-800 transition text-sm flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar Cuenta
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $reports->links() }}
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="text-center py-16">
                        <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-brown mb-2">No hay reportes</h3>
                        <p class="text-gray-500">
                            @if(request('status'))
                                No hay reportes con el estado seleccionado.
                            @else
                                No se han recibido reportes aún.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
