<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">Editar Plan: {{ $plan->nombre }}</h1>
                        <p class="text-white/80 mt-1">Modifica las caracteristicas del plan</p>
                    </div>
                    <a href="{{ route('admin.plans.index') }}" class="px-4 py-2 bg-white/20 text-white rounded-lg font-semibold hover:bg-white/30">
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informacion Basica -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Informacion Basica</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Plan *</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $plan->nombre) }}" required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                @error('nombre')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Slug (identificador) *</label>
                                <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Descripcion</label>
                            <textarea name="descripcion" rows="2" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">{{ old('descripcion', $plan->descripcion) }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Precio Mensual (euros) *</label>
                                <input type="number" name="precio_mensual" value="{{ old('precio_mensual', $plan->precio_mensual) }}" step="0.01" min="0" required
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                @error('precio_mensual')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Precio Anual (euros)</label>
                                <input type="number" name="precio_anual" value="{{ old('precio_anual', $plan->precio_anual) }}" step="0.01" min="0"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                <p class="text-xs text-gray-500 mt-1">Opcional: precio si paga anual</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Orden</label>
                                <input type="number" name="orden" value="{{ old('orden', $plan->orden) }}" min="0"
                                       class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-brown">
                                <p class="text-xs text-gray-500 mt-1">Orden de visualizacion</p>
                            </div>
                        </div>
                    </div>

                    <!-- Limites (0 = ilimitado) -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Limites</h2>
                        <p class="text-sm text-gray-500 mb-4">Dejar en 0 para ilimitado</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Likes Diarios</label>
                                <input type="number" name="likes_diarios" value="{{ old('likes_diarios', $plan->likes_diarios) }}" min="0"
                                       class="w-full px-4 py-3 border rounded-lg" placeholder="0 = Ilimitado">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mensajes Gratis/Semana</label>
                                <input type="number" name="mensajes_semanales_gratis" value="{{ old('mensajes_semanales_gratis', $plan->mensajes_semanales_gratis) }}" min="0"
                                       class="w-full px-4 py-3 border rounded-lg" placeholder="Solo para mensajes a usuarios gratis">
                            </div>
                        </div>
                    </div>

                    <!-- Caracteristicas Premium (solo las implementadas) -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Caracteristicas</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50">
                                <input type="hidden" name="puede_iniciar_conversacion" value="0">
                                <input type="checkbox" name="puede_iniciar_conversacion" value="1" {{ old('puede_iniciar_conversacion', $plan->puede_iniciar_conversacion) ? 'checked' : '' }}
                                       class="w-5 h-5 text-brown rounded">
                                <span class="text-sm font-semibold">Puede iniciar conversaciones</span>
                            </label>

                            <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50">
                                <input type="hidden" name="mensajes_ilimitados" value="0">
                                <input type="checkbox" name="mensajes_ilimitados" value="1" {{ old('mensajes_ilimitados', $plan->mensajes_ilimitados) ? 'checked' : '' }}
                                       class="w-5 h-5 text-brown rounded">
                                <span class="text-sm font-semibold">Mensajes ilimitados</span>
                            </label>
                        </div>
                    </div>

                    <!-- Campos ocultos para mantener compatibilidad con la BD -->
                    <input type="hidden" name="ver_quien_te_gusta" value="{{ $plan->ver_quien_te_gusta ? '1' : '0' }}">
                    <input type="hidden" name="matches_ilimitados" value="{{ $plan->matches_ilimitados ? '1' : '0' }}">
                    <input type="hidden" name="rewind" value="{{ $plan->rewind ? '1' : '0' }}">
                    <input type="hidden" name="sin_anuncios" value="{{ $plan->sin_anuncios ? '1' : '0' }}">
                    <input type="hidden" name="modo_incognito" value="{{ $plan->modo_incognito ? '1' : '0' }}">
                    <input type="hidden" name="verificacion_prioritaria" value="{{ $plan->verificacion_prioritaria ? '1' : '0' }}">
                    <input type="hidden" name="boost_mensual" value="{{ $plan->boost_mensual ?? 0 }}">
                    <input type="hidden" name="fotos_adicionales" value="{{ $plan->fotos_adicionales ?? 0 }}">

                    <!-- Estado -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Estado del Plan</h2>
                        <label class="flex items-center space-x-3 cursor-pointer p-4 border-2 rounded-lg hover:bg-gray-50 {{ old('activo', $plan->activo) ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" name="activo" value="1" {{ old('activo', $plan->activo) ? 'checked' : '' }}
                                   class="w-6 h-6 text-green-600 rounded">
                            <div>
                                <span class="text-base font-bold">Plan Activo</span>
                                <p class="text-sm text-gray-600">Los usuarios podran suscribirse a este plan</p>
                            </div>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.plans.index') }}" class="px-6 py-3 bg-gray-200 rounded-lg font-bold">Cancelar</a>
                        <button type="submit" class="px-6 py-3 bg-brown text-white rounded-lg font-bold hover:bg-brown-dark">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
