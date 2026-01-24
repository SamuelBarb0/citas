<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">Gestión de Planes de Suscripción</h1>
                        <p class="text-white/80 mt-1">Configura los planes Premium y VIP</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-4 py-2 bg-white/20 text-white rounded-lg font-semibold hover:bg-white/30">
                            ← Volver al Panel
                        </a>
                        <a href="{{ route('admin.plans.create') }}"
                           class="px-6 py-3 bg-white text-brown rounded-xl font-bold hover:shadow-lg transition">
                            + Crear Nuevo Plan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 p-4 rounded">
                        <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Planes Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($plans as $plan)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden {{ !$plan->activo ? 'opacity-60' : '' }}">
                            <!-- Header del Plan -->
                            <div class="p-6 bg-gradient-to-br from-brown to-brown-dark text-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-2xl font-black">{{ $plan->nombre }}</h3>
                                        <p class="text-white/80 text-sm mt-1">{{ $plan->slug }}</p>
                                    </div>
                                    @if(!$plan->activo)
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                                            Inactivo
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                            Activo
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <span class="text-4xl font-black">€{{ number_format($plan->precio_mensual, 2) }}</span>
                                    <span class="text-white/80">/ mes</span>
                                    @if($plan->precio_anual)
                                        <div class="mt-2 text-sm">
                                            <span class="text-white/90">€{{ number_format($plan->precio_anual, 2) }}/año</span>
                                            @if($plan->descuento_anual > 0)
                                                <span class="ml-2 px-2 py-1 bg-green-500 text-white text-xs rounded">
                                                    Ahorra {{ $plan->descuento_anual }}%
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Descripción y Características -->
                            <div class="p-6">
                                @if($plan->descripcion)
                                    <p class="text-gray-600 text-sm mb-4">{{ $plan->descripcion }}</p>
                                @endif

                                <div class="space-y-2 mb-4">
                                    <!-- Características personalizadas -->
                                    @if($plan->caracteristicas_personalizadas && count($plan->caracteristicas_personalizadas) > 0)
                                        @foreach($plan->caracteristicas_personalizadas as $caracteristica)
                                            <div class="flex items-center text-sm text-gray-700">
                                                <span class="text-brown mr-2">✓</span>
                                                <span>{{ $caracteristica }}</span>
                                            </div>
                                        @endforeach
                                    @endif

                                    <!-- Características del sistema -->
                                    @if($plan->likes_diarios)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <span class="text-brown mr-2">✓</span>
                                            <span>{{ $plan->likes_diarios }} likes diarios</span>
                                        </div>
                                    @endif

                                    @if($plan->mensajes_ilimitados)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <span class="text-brown mr-2">✓</span>
                                            <span>Mensajes ilimitados</span>
                                        </div>
                                    @elseif($plan->mensajes_semanales_gratis)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <span class="text-brown mr-2">✓</span>
                                            <span>{{ $plan->mensajes_semanales_gratis }} mensajes gratis/semana</span>
                                        </div>
                                    @endif

                                    @if($plan->puede_iniciar_conversacion)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <span class="text-brown mr-2">✓</span>
                                            <span>Puede iniciar conversaciones</span>
                                        </div>
                                    @endif

                                    @if($plan->ver_quien_te_gusta)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <span class="text-brown mr-2">✓</span>
                                            <span>Ver quién te ha dado like</span>
                                        </div>
                                    @endif

                                    @if($plan->fotos_adicionales)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <span class="text-brown mr-2">✓</span>
                                            <span>Hasta {{ $plan->fotos_adicionales }} fotos en tu perfil</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="p-6 bg-gray-50 border-t flex gap-2">
                                <a href="{{ route('admin.plans.edit', $plan->id) }}"
                                   class="flex-1 px-4 py-2 bg-brown text-white rounded-lg text-center font-bold hover:bg-brown-dark">
                                    Editar
                                </a>
                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este plan? Asegúrate de que no haya suscripciones activas.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($plans->count() === 0)
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <p class="text-gray-500 text-lg mb-4">No hay planes creados aún</p>
                        <a href="{{ route('admin.plans.create') }}"
                           class="inline-block px-6 py-3 bg-brown text-white rounded-lg font-bold hover:bg-brown-dark">
                            Crear primer plan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
