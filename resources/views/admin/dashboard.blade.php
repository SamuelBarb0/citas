<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">👋 Hola, {{ Auth::user()->name }}</h1>
                        <p class="text-white/90 mt-1 text-lg">Aquí puedes gestionar todo sobre Citas Mallorca</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                       class="px-6 py-3 bg-white text-brown rounded-xl font-bold hover:shadow-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a la App
                    </a>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Resumen Rápido -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-brown mb-4">📊 ¿Cómo va la plataforma?</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <!-- Total Usuarios -->
                        <div class="bg-white rounded-2xl shadow-md p-4 text-center">
                            <div class="text-4xl mb-2">👥</div>
                            <p class="text-3xl font-black text-blue-600">{{ $stats['total_users'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">Usuarios registrados</p>
                        </div>

                        <!-- Perfiles Activos -->
                        <div class="bg-white rounded-2xl shadow-md p-4 text-center">
                            <div class="text-4xl mb-2">✅</div>
                            <p class="text-3xl font-black text-green-600">{{ $stats['total_profiles'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">Perfiles completos</p>
                        </div>

                        <!-- Total Matches -->
                        <div class="bg-white rounded-2xl shadow-md p-4 text-center">
                            <div class="text-4xl mb-2">💕</div>
                            <p class="text-3xl font-black text-pink-600">{{ $stats['total_matches'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">Matches creados</p>
                        </div>

                        <!-- Total Likes -->
                        <div class="bg-white rounded-2xl shadow-md p-4 text-center">
                            <div class="text-4xl mb-2">❤️</div>
                            <p class="text-3xl font-black text-red-600">{{ $stats['total_likes'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">Me gusta dados</p>
                        </div>

                        <!-- Perfiles Verificados -->
                        <div class="bg-white rounded-2xl shadow-md p-4 text-center">
                            <div class="text-4xl mb-2">✔️</div>
                            <p class="text-3xl font-black text-blue-600">{{ $stats['verified_profiles'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">Perfiles verificados</p>
                        </div>

                        <!-- Reportes Pendientes -->
                        <div class="bg-white rounded-2xl shadow-md p-4 text-center {{ $stats['pending_reports'] > 0 ? 'ring-2 ring-yellow-400' : '' }}">
                            <div class="text-4xl mb-2">⚠️</div>
                            <p class="text-3xl font-black text-yellow-600">{{ $stats['pending_reports'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">Reportes pendientes</p>
                            @if($stats['pending_reports'] > 0)
                                <a href="{{ route('admin.reports') }}" class="text-xs text-yellow-700 font-bold hover:underline mt-1 inline-block">
                                    ¡Revisar ahora!
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 🔥 TAREAS URGENTES / IMPORTANTES -->
                @if($stats['pending_reports'] > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-red-600 mb-4 flex items-center gap-2">
                        🚨 ¡Atención! Tareas pendientes
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.reports') }}"
                           class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition group border-2 border-yellow-600">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">⚠️</div>
                                <div>
                                    <h3 class="font-black text-gray-900 text-lg">Reportes sin revisar</h3>
                                    <p class="text-sm text-gray-800 font-semibold">Hay {{ $stats['pending_reports'] }} usuarios que reportaron problemas</p>
                                    <p class="text-xs text-gray-700 mt-1">→ Haz clic para revisarlos ahora</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.verification') }}"
                           class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-2xl shadow-xl p-6 hover:shadow-2xl transition group border-2 border-blue-600">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">✔️</div>
                                <div>
                                    <h3 class="font-black text-white text-lg">Verificar perfiles</h3>
                                    <p class="text-sm text-white font-semibold">Revisa las solicitudes de verificación</p>
                                    <p class="text-xs text-white/90 mt-1">→ Aprobar o rechazar perfiles</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endif

                <!-- 👥 GESTIÓN DE USUARIOS -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-brown mb-4">👥 Gestionar Usuarios</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('admin.users') }}"
                           class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition group border-l-4 border-green-500">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">👤</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-gray-900 text-lg">Ver todos los usuarios</h3>
                                    <p class="text-sm text-gray-600 mt-1">Buscar, editar o eliminar usuarios</p>
                                    <p class="text-xs text-gray-500 mt-2">Total: {{ $stats['total_users'] }} usuarios</p>
                                </div>
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-green-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.verification') }}"
                           class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition group border-l-4 border-blue-500">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">✅</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-gray-900 text-lg">Verificar perfiles</h3>
                                    <p class="text-sm text-gray-600 mt-1">Aprobar o rechazar verificaciones</p>
                                    <p class="text-xs text-gray-500 mt-2">Ya verificados: {{ $stats['verified_profiles'] }} perfiles</p>
                                </div>
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 💰 PLANES Y SUSCRIPCIONES -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-brown mb-4">💰 Planes y Pagos</h2>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        <a href="{{ route('admin.plans.index') }}"
                           class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition group">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">💳</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-white text-lg">Gestionar planes de suscripción</h3>
                                    <p class="text-sm text-white/90 mt-1">Crear, editar o eliminar planes (Básico, Premium, VIP, etc.)</p>
                                    <p class="text-xs text-white/80 mt-2">→ Cambiar precios, funciones y duración de cada plan</p>
                                </div>
                                <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 📝 CONTENIDOS Y TEXTOS -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-brown mb-4">📝 Editar Textos de la Web</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('admin.content.index') }}"
                           class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition group">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">📄</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-white text-lg">Contenidos de la página</h3>
                                    <p class="text-sm text-white/90 mt-1">Cambiar textos de inicio, secciones, etc.</p>
                                    <p class="text-xs text-white/80 mt-2">→ Sin tocar código, solo escribir</p>
                                </div>
                                <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.seo.index') }}"
                           class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition group">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">🔍</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-white text-lg">SEO - Aparecer en Google</h3>
                                    <p class="text-sm text-white/90 mt-1">Configurar cómo se ve la web en Google</p>
                                    <p class="text-xs text-white/80 mt-2">→ Títulos, descripciones para buscadores</p>
                                </div>
                                <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 📊 ESTADÍSTICAS Y REPORTES -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-brown mb-4">📊 Ver Estadísticas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('admin.statistics') }}"
                           class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition group">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">📈</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-white text-lg">Estadísticas completas</h3>
                                    <p class="text-sm text-white/90 mt-1">Gráficos y números de actividad</p>
                                    <p class="text-xs text-white/80 mt-2">→ Ver cuántos usuarios, matches, likes por fecha</p>
                                </div>
                                <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.logs') }}"
                           class="bg-gradient-to-r from-gray-600 to-gray-700 rounded-2xl shadow-lg p-6 hover:shadow-xl transition group">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl">📋</div>
                                <div class="flex-1">
                                    <h3 class="font-black text-white text-lg">Historial de cambios</h3>
                                    <p class="text-sm text-white/90 mt-1">Ver qué se ha modificado y cuándo</p>
                                    <p class="text-xs text-white/80 mt-2">→ Registro de todas las acciones del admin</p>
                                </div>
                                <svg class="w-6 h-6 text-white group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
