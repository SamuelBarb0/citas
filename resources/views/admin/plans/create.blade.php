<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-black text-white">Crear Nuevo Plan</h1>
                    <a href="{{ route('admin.plans.index') }}" class="px-4 py-2 bg-white/20 text-white rounded-lg font-semibold hover:bg-white/30">
                        ← Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('admin.plans.store') }}" method="POST">
                    @csrf

                    <!-- Información Básica -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Información Básica</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Plan *</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown"
                                       placeholder="Ej: Premium, VIP, Gold">
                                @error('nombre')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Slug (identificador) *</label>
                                <input type="text" name="slug" value="{{ old('slug') }}" required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown"
                                       placeholder="ej: premium, vip, gold">
                                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="2" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown"
                                      placeholder="Breve descripción del plan">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Precio Mensual (€) *</label>
                                <input type="number" name="precio_mensual" value="{{ old('precio_mensual') }}" step="0.01" min="0" required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                @error('precio_mensual')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Precio Anual (€)</label>
                                <input type="number" name="precio_anual" value="{{ old('precio_anual') }}" step="0.01" min="0"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                <p class="text-xs text-gray-500 mt-1">Opcional: precio si paga anual</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Orden</label>
                                <input type="number" name="orden" value="{{ old('orden', 0) }}" min="0"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                <p class="text-xs text-gray-500 mt-1">Orden de visualización</p>
                            </div>
                        </div>
                    </div>

                    <!-- Límites y Características -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Límites y Características</h2>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Likes Diarios</label>
                                <input type="number" name="likes_diarios" value="{{ old('likes_diarios', 0) }}" min="0"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                <p class="text-xs text-gray-500 mt-1">0 = Ilimitados</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mensajes Gratis/Semana</label>
                                <input type="number" name="mensajes_semanales_gratis" value="{{ old('mensajes_semanales_gratis', 0) }}" min="0"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 mb-3">Permisos</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50">
                                <input type="hidden" name="puede_iniciar_conversacion" value="0">
                                <input type="checkbox" name="puede_iniciar_conversacion" value="1" {{ old('puede_iniciar_conversacion') ? 'checked' : '' }}
                                       class="w-5 h-5 text-brown rounded">
                                <span class="text-sm font-semibold">Puede iniciar conversaciones</span>
                            </label>

                            <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50">
                                <input type="hidden" name="mensajes_ilimitados" value="0">
                                <input type="checkbox" name="mensajes_ilimitados" value="1" {{ old('mensajes_ilimitados') ? 'checked' : '' }}
                                       class="w-5 h-5 text-brown rounded">
                                <span class="text-sm font-semibold">Mensajes ilimitados</span>
                            </label>
                        </div>
                    </div>

                    <!-- Campos ocultos para características no implementadas (mantener compatibilidad DB) -->
                    <input type="hidden" name="ver_quien_te_gusta" value="0">
                    <input type="hidden" name="matches_ilimitados" value="0">
                    <input type="hidden" name="rewind" value="0">
                    <input type="hidden" name="sin_anuncios" value="0">
                    <input type="hidden" name="modo_incognito" value="0">
                    <input type="hidden" name="verificacion_prioritaria" value="0">
                    <input type="hidden" name="boost_mensual" value="0">
                    <input type="hidden" name="fotos_adicionales" value="0">

                    <!-- Estado -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Estado del Plan</h2>
                        <label class="flex items-center space-x-3 cursor-pointer p-4 border-2 rounded-lg hover:bg-gray-50 border-green-500 bg-green-50">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}
                                   class="w-6 h-6 text-green-600 rounded">
                            <div>
                                <span class="text-base font-bold">Plan Activo</span>
                                <p class="text-sm text-gray-600">Los usuarios podrán suscribirse a este plan</p>
                            </div>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.plans.index') }}" class="px-6 py-3 bg-gray-200 rounded-lg font-bold">Cancelar</a>
                        <button type="submit" class="px-6 py-3 bg-brown text-white rounded-lg font-bold hover:bg-brown-dark">Crear Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
