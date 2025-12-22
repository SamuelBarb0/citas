<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-600 to-gray-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-black text-white">Logs de Actividad</h1>
                            <p class="text-white/80 mt-1">Historial de acciones administrativas</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-white/60 text-sm">Total de registros</p>
                        <p class="text-2xl font-bold text-white">{{ $logs->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Filtros -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <form action="{{ route('admin.logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Filtrar por acción -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Acción</label>
                            <select name="action" class="w-full rounded-xl border-gray-300 focus:ring-gray-500 focus:border-gray-500">
                                <option value="">Todas las acciones</option>
                                <option value="verify_profile" {{ request('action') == 'verify_profile' ? 'selected' : '' }}>Verificar Perfil</option>
                                <option value="unverify_profile" {{ request('action') == 'unverify_profile' ? 'selected' : '' }}>Quitar Verificación</option>
                                <option value="suspend_user" {{ request('action') == 'suspend_user' ? 'selected' : '' }}>Suspender Usuario</option>
                                <option value="activate_user" {{ request('action') == 'activate_user' ? 'selected' : '' }}>Activar Usuario</option>
                                <option value="resolve_report" {{ request('action') == 'resolve_report' ? 'selected' : '' }}>Resolver Reporte</option>
                                <option value="reject_report" {{ request('action') == 'reject_report' ? 'selected' : '' }}>Rechazar Reporte</option>
                            </select>
                        </div>

                        <!-- Filtrar por admin -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Administrador</label>
                            <select name="admin_id" class="w-full rounded-xl border-gray-300 focus:ring-gray-500 focus:border-gray-500">
                                <option value="">Todos los admins</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-end gap-3">
                            <button type="submit" class="flex-1 px-6 py-3 bg-gray-600 text-white rounded-xl font-bold hover:bg-gray-700 transition">
                                Filtrar
                            </button>
                            @if(request('action') || request('admin_id'))
                                <a href="{{ route('admin.logs') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Tabla de Logs -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    @if($logs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Fecha/Hora
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Admin
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Acción
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Detalles
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($logs as $log)
                                        <tr class="hover:bg-gray-50 transition">
                                            <!-- Fecha/Hora -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $log->created_at->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $log->created_at->format('H:i:s') }}
                                                </div>
                                            </td>

                                            <!-- Admin -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white font-bold text-sm">
                                                            {{ substr($log->admin->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-semibold text-gray-900">{{ $log->admin->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $log->admin->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Acción -->
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $actionColors = [
                                                        'verify_profile' => 'bg-blue-100 text-blue-800',
                                                        'unverify_profile' => 'bg-gray-100 text-gray-800',
                                                        'suspend_user' => 'bg-red-100 text-red-800',
                                                        'activate_user' => 'bg-green-100 text-green-800',
                                                        'resolve_report' => 'bg-purple-100 text-purple-800',
                                                        'reject_report' => 'bg-yellow-100 text-yellow-800',
                                                    ];
                                                    $actionIcons = [
                                                        'verify_profile' => '✓',
                                                        'unverify_profile' => '✗',
                                                        'suspend_user' => '⊗',
                                                        'activate_user' => '✓',
                                                        'resolve_report' => '✓',
                                                        'reject_report' => '↺',
                                                    ];
                                                    $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800';
                                                    $icon = $actionIcons[$log->action] ?? '•';
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $color }}">
                                                    {{ $icon }} {{ str_replace('_', ' ', ucwords($log->action, '_')) }}
                                                </span>
                                            </td>

                                            <!-- Descripción -->
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-900">{{ $log->description }}</p>
                                                @if($log->target_type)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Objetivo: {{ $log->target_type }} #{{ $log->target_id }}
                                                    </p>
                                                @endif
                                            </td>

                                            <!-- Metadata -->
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($log->metadata && count($log->metadata) > 0)
                                                    <button
                                                        onclick="alert('{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}')"
                                                        class="text-gray-600 hover:text-gray-900 font-semibold"
                                                    >
                                                        Ver datos →
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <!-- Sin resultados -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No hay logs registrados</h3>
                            <p class="text-gray-500">
                                @if(request('action') || request('admin_id'))
                                    No se encontraron logs con los filtros aplicados.
                                @else
                                    Aún no se han registrado acciones administrativas.
                                @endif
                            </p>
                            @if(request('action') || request('admin_id'))
                                <a href="{{ route('admin.logs') }}" class="mt-4 inline-block px-6 py-2 bg-gray-600 text-white rounded-xl font-bold hover:bg-gray-700 transition">
                                    Limpiar Filtros
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Estadísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                    @php
                        $actionStats = [
                            'verify_profile' => ['label' => 'Verificaciones', 'color' => 'blue', 'icon' => '✓'],
                            'suspend_user' => ['label' => 'Suspensiones', 'color' => 'red', 'icon' => '⊗'],
                            'activate_user' => ['label' => 'Activaciones', 'color' => 'green', 'icon' => '✓'],
                            'resolve_report' => ['label' => 'Reportes Resueltos', 'color' => 'purple', 'icon' => '✓'],
                        ];
                    @endphp

                    @foreach($actionStats as $action => $info)
                        @php
                            $count = \App\Models\AdminLog::where('action', $action)->count();
                        @endphp
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-{{ $info['color'] }}-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-xs font-semibold uppercase">{{ $info['label'] }}</p>
                                    <p class="text-2xl font-black text-gray-900 mt-1">{{ $count }}</p>
                                </div>
                                <div class="w-12 h-12 bg-{{ $info['color'] }}-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">{{ $info['icon'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
