<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-black text-white">Estadísticas Avanzadas</h1>
                            <p class="text-white/80 mt-1">Métricas detalladas y análisis de crecimiento</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Estadísticas Generales en Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Usuarios -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-500 text-xs font-semibold uppercase">Total Usuarios</p>
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($stats['total_users']) }}</p>
                                <div class="mt-3 space-y-1">
                                    <p class="text-xs text-gray-600">Hoy: <span class="font-bold text-blue-600">+{{ $stats['users_today'] }}</span></p>
                                    <p class="text-xs text-gray-600">Esta semana: <span class="font-bold text-blue-600">+{{ $stats['users_week'] }}</span></p>
                                    <p class="text-xs text-gray-600">Este mes: <span class="font-bold text-blue-600">+{{ $stats['users_month'] }}</span></p>
                                </div>
                            </div>
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Matches -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-pink-500">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-500 text-xs font-semibold uppercase">Total Matches</p>
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($stats['total_matches']) }}</p>
                                <div class="mt-3 space-y-1">
                                    <p class="text-xs text-gray-600">Hoy: <span class="font-bold text-pink-600">+{{ $stats['matches_today'] }}</span></p>
                                    <p class="text-xs text-gray-600">Esta semana: <span class="font-bold text-pink-600">+{{ $stats['matches_week'] }}</span></p>
                                </div>
                            </div>
                            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Mensajes -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-500 text-xs font-semibold uppercase">Total Mensajes</p>
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($stats['total_messages']) }}</p>
                                <div class="mt-3 space-y-1">
                                    <p class="text-xs text-gray-600">Hoy: <span class="font-bold text-green-600">+{{ $stats['messages_today'] }}</span></p>
                                </div>
                            </div>
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Total Likes -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-gray-500 text-xs font-semibold uppercase">Total Likes</p>
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ number_format($stats['total_likes']) }}</p>
                            </div>
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gráfico de Usuarios por Mes -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            Usuarios Registrados (6 meses)
                        </h3>
                        <canvas id="usersChart" class="max-h-80"></canvas>
                    </div>

                    <!-- Gráfico de Matches por Mes -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                            Matches Creados (6 meses)
                        </h3>
                        <canvas id="matchesChart" class="max-h-80"></canvas>
                    </div>
                </div>

                <!-- Top Rankings -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top Usuarios con Más Matches -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="text-2xl">🏆</span>
                            Top 10 - Más Matches
                        </h3>
                        <div class="space-y-3">
                            @foreach($topMatches as $index => $user)
                                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                    <!-- Posición -->
                                    <div class="w-8 h-8 flex items-center justify-center rounded-full font-black text-sm
                                        {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-orange-300 text-orange-900' : 'bg-gray-200 text-gray-600')) }}">
                                        {{ $index + 1 }}
                                    </div>

                                    <!-- Foto -->
                                    @if($user->profile)
                                        <img
                                            src="{{ $user->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=A67C52&color=fff' }}"
                                            alt="{{ $user->profile->nombre }}"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow"
                                        >
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brown to-heart-red flex items-center justify-center">
                                            <span class="text-white font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 truncate">
                                            {{ $user->profile->nombre ?? $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>

                                    <!-- Contador -->
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-pink-600">{{ $user->matches_count }}</p>
                                        <p class="text-xs text-gray-500">matches</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Usuarios Más Activos -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span class="text-2xl">⚡</span>
                            Top 10 - Más Activos
                        </h3>
                        <div class="space-y-3">
                            @foreach($mostActive as $index => $user)
                                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                    <!-- Posición -->
                                    <div class="w-8 h-8 flex items-center justify-center rounded-full font-black text-sm
                                        {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-orange-300 text-orange-900' : 'bg-gray-200 text-gray-600')) }}">
                                        {{ $index + 1 }}
                                    </div>

                                    <!-- Foto -->
                                    @if($user->profile)
                                        <img
                                            src="{{ $user->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=A67C52&color=fff' }}"
                                            alt="{{ $user->profile->nombre }}"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow"
                                        >
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brown to-heart-red flex items-center justify-center">
                                            <span class="text-white font-bold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 truncate">
                                            {{ $user->profile->nombre ?? $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>

                                    <!-- Contador -->
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-blue-600">{{ $user->likes_count }}</p>
                                        <p class="text-xs text-gray-500">likes dados</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Adicionales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Perfiles Activos</p>
                                <p class="text-2xl font-black text-gray-900">{{ $stats['active_profiles'] }}</p>
                                <p class="text-xs text-gray-500">de {{ $stats['total_profiles'] }} totales</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Perfiles Verificados</p>
                                <p class="text-2xl font-black text-gray-900">{{ $stats['verified_profiles'] }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $stats['total_profiles'] > 0 ? round(($stats['verified_profiles'] / $stats['total_profiles']) * 100, 1) : 0 }}% del total
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Reportes Pendientes</p>
                                <p class="text-2xl font-black text-gray-900">{{ $stats['pending_reports'] }}</p>
                                <a href="{{ route('admin.reports') }}" class="text-xs text-yellow-600 hover:text-yellow-700 font-semibold">
                                    Ver reportes →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos de usuarios por mes
            const usersData = @json($usersByMonth);
            const usersLabels = usersData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
            });
            const usersCounts = usersData.map(item => item.count);

            // Gráfico de Usuarios
            const usersCtx = document.getElementById('usersChart').getContext('2d');
            new Chart(usersCtx, {
                type: 'bar',
                data: {
                    labels: usersLabels,
                    datasets: [{
                        label: 'Usuarios Registrados',
                        data: usersCounts,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Datos de matches por mes
            const matchesData = @json($matchesByMonth);
            const matchesLabels = matchesData.map(item => {
                const [year, month] = item.month.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
            });
            const matchesCounts = matchesData.map(item => item.count);

            // Gráfico de Matches
            const matchesCtx = document.getElementById('matchesChart').getContext('2d');
            new Chart(matchesCtx, {
                type: 'line',
                data: {
                    labels: matchesLabels,
                    datasets: [{
                        label: 'Matches Creados',
                        data: matchesCounts,
                        backgroundColor: 'rgba(236, 72, 153, 0.2)',
                        borderColor: 'rgba(236, 72, 153, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(236, 72, 153, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
