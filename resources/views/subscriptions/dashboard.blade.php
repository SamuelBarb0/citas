@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-black text-brown mb-2">Mi Suscripción</h1>
            <p class="text-gray-600">Gestiona tu plan y métodos de pago</p>
        </div>

        @if($subscription)
            <!-- Plan Actual -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Información del Plan -->
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-r from-heart-red to-heart-red-light rounded-3xl shadow-2xl p-8 text-white">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="inline-block bg-white/20 px-4 py-1 rounded-full text-sm font-bold mb-3">
                                    Plan {{ $subscription->plan->nombre }}
                                </div>
                                <h2 class="text-3xl font-black mb-2">Suscripción Activa</h2>
                                <p class="text-white/80">Disfruta de todos los beneficios premium</p>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-black">€{{ number_format($subscription->monto_pagado, 2) }}</div>
                                <p class="text-white/70 text-sm">{{ ucfirst($subscription->tipo) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/30">
                            <div>
                                <p class="text-white/70 text-sm mb-1">Inicio</p>
                                <p class="font-bold">{{ $subscription->fecha_inicio->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm mb-1">Renovación</p>
                                <p class="font-bold">{{ $subscription->fecha_expiracion->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-white/70 text-sm mb-1">Estado</p>
                                <p class="font-bold">
                                    @if($subscription->estado === 'activa')
                                        ✓ Activa
                                    @else
                                        {{ ucfirst($subscription->estado) }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($subscription->estado === 'cancelada_fin_periodo')
                            <div class="mt-6 bg-yellow-500/20 rounded-2xl p-4">
                                <p class="text-sm">
                                    ⚠️ <strong>Suscripción cancelada.</strong> Mantienes acceso hasta el
                                    <strong>{{ $subscription->fecha_expiracion->format('d/m/Y') }}</strong>
                                    <br>
                                    No se realizará el siguiente cobro.
                                </p>
                            </div>
                        @elseif($subscription->auto_renovacion)
                            <div class="mt-6 bg-white/10 rounded-2xl p-4">
                                <p class="text-sm">
                                    ✓ Tu suscripción se renovará automáticamente el
                                    <strong>{{ $subscription->fecha_expiracion->format('d/m/Y') }}</strong>
                                </p>
                            </div>
                        @else
                            <div class="mt-6 bg-yellow-500/20 rounded-2xl p-4">
                                <p class="text-sm">
                                    ⚠️ La renovación automática está desactivada. Tu plan expirará el
                                    <strong>{{ $subscription->fecha_expiracion->format('d/m/Y') }}</strong>
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <a href="{{ route('subscriptions.index') }}"
                           class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center">
                            <div class="text-3xl mb-2">🔄</div>
                            <h3 class="font-bold text-brown mb-1">Cambiar Plan</h3>
                            <p class="text-gray-600 text-sm">Actualiza o mejora tu plan</p>
                        </a>

                        @if($subscription->auto_renovacion)
                            <form action="{{ route('subscriptions.cancel') }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de cancelar la renovación automática?');">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center h-full">
                                    <div class="text-3xl mb-2">🚫</div>
                                    <h3 class="font-bold text-brown mb-1">Cancelar Renovación</h3>
                                    <p class="text-gray-600 text-sm">Mantén acceso hasta expiración</p>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('subscriptions.reactivate') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center h-full">
                                    <div class="text-3xl mb-2">✓</div>
                                    <h3 class="font-bold text-brown mb-1">Reactivar</h3>
                                    <p class="text-gray-600 text-sm">Activar renovación automática</p>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Uso y Límites -->
                <div class="space-y-6">
                    <!-- Likes -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-brown mb-4 flex items-center gap-2">
                            <span class="text-2xl">❤️</span>
                            Likes Diarios
                        </h3>
                        @if($subscription->plan->likes_diarios === -1)
                            <p class="text-3xl font-black text-heart-red mb-2">Ilimitados</p>
                            <p class="text-gray-600 text-sm">Da todos los likes que quieras</p>
                        @elseif($subscription->plan->likes_diarios > 0)
                            <div class="mb-3">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Usados hoy</span>
                                    <span class="font-bold text-brown">
                                        {{ $subscription->likes_usados_hoy }} / {{ $subscription->plan->likes_diarios }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-heart-red to-heart-red-light h-3 rounded-full transition-all"
                                         style="width: {{ min(100, ($subscription->likes_usados_hoy / $subscription->plan->likes_diarios) * 100) }}%"></div>
                                </div>
                            </div>
                            <p class="text-gray-600 text-xs">
                                Se restablecen en {{ $subscription->ultimo_reset_likes ? $subscription->ultimo_reset_likes->addDay()->diffForHumans() : '24 horas' }}
                            </p>
                        @else
                            <p class="text-3xl font-black text-heart-red mb-2">Ilimitados</p>
                            <p class="text-gray-600 text-sm">Da todos los likes que quieras</p>
                        @endif
                    </div>


                    <!-- Boosts -->
                    @if($subscription->plan->boost_mensual)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="font-bold text-brown mb-4 flex items-center gap-2">
                                <span class="text-2xl">🚀</span>
                                Boosts
                            </h3>
                            <p class="text-3xl font-black text-purple-500 mb-2">{{ $subscription->boosts_restantes }}</p>
                            <p class="text-gray-600 text-sm">Disponibles este mes</p>
                            @if($subscription->ultimo_boost)
                                <p class="text-gray-500 text-xs mt-2">
                                    Último uso: {{ $subscription->ultimo_boost->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Características Premium -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="font-bold text-brown mb-4">Características</h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $tieneCaracteristicas = false;
                            @endphp

                            @if($subscription->plan->mensajes_ilimitados)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Mensajes ilimitados
                                </div>
                            @endif
                            @if($subscription->plan->puede_iniciar_conversacion)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Iniciar conversaciones
                                </div>
                            @endif
                            @if($subscription->plan->ver_quien_te_gusta)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ver quién te gusta
                                </div>
                            @endif
                            @if($subscription->plan->matches_ilimitados)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Matches ilimitados
                                </div>
                            @endif
                            @if($subscription->plan->rewind)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Deshacer swipes
                                </div>
                            @endif
                            @if($subscription->plan->sin_anuncios)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Sin anuncios
                                </div>
                            @endif
                            @if($subscription->plan->modo_incognito)
                                @php $tieneCaracteristicas = true; @endphp
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Modo incógnito
                                </div>
                            @endif

                            @if(!$tieneCaracteristicas)
                                <p class="text-gray-500 text-center py-2">Plan básico activo</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Pagos -->
            @if($history->count() > 0)
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-brown mb-6">Historial de Suscripciones</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="border-b-2 border-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 font-bold text-brown text-sm">Fecha</th>
                                    <th class="text-left py-3 px-4 font-bold text-brown text-sm">Plan</th>
                                    <th class="text-left py-3 px-4 font-bold text-brown text-sm">Período</th>
                                    <th class="text-left py-3 px-4 font-bold text-brown text-sm">Monto</th>
                                    <th class="text-left py-3 px-4 font-bold text-brown text-sm">Método</th>
                                    <th class="text-left py-3 px-4 font-bold text-brown text-sm">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($history as $sub)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 text-gray-600 text-sm">
                                            {{ $sub->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-brown">
                                            {{ $sub->plan->nombre }}
                                        </td>
                                        <td class="py-3 px-4 text-gray-600 text-sm">
                                            {{ ucfirst($sub->tipo) }}
                                        </td>
                                        <td class="py-3 px-4 font-bold text-heart-red">
                                            €{{ number_format($sub->monto_pagado, 2) }}
                                        </td>
                                        <td class="py-3 px-4 text-gray-600 text-sm">
                                            {{ ucfirst($sub->metodo_pago ?? 'N/A') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($sub->estado === 'activa')
                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Activa</span>
                                            @elseif($sub->estado === 'cancelada')
                                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Cancelada</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($sub->estado) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @else
            <!-- Sin Suscripción -->
            <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-brown mb-3">No Tienes Suscripción Activa</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Mejora tu experiencia con un plan premium y encuentra más matches
                </p>
                <a href="{{ route('subscriptions.index') }}"
                   class="inline-block bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-8 rounded-2xl font-bold hover:shadow-glow transition">
                    Ver Planes Disponibles
                </a>
            </div>
        @endif

        <!-- Sección de Gestión de Cuenta -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mt-8 border-2 border-gray-100">
            <h3 class="text-2xl font-bold text-brown mb-2">Gestion de Cuenta</h3>
            <p class="text-gray-600 mb-6">Opciones para gestionar tu suscripcion y tu cuenta en la plataforma.</p>

            <!-- BOTÓN DE PRUEBAS -->
            @if($subscription)
            <div class="bg-purple-50 border-2 border-purple-300 rounded-2xl p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-2xl">🧪</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-purple-800 text-lg mb-2">🧪 MODO PRUEBAS - Cancelación Forzada</h4>
                        <p class="text-purple-700 text-sm mb-4">
                            <strong>⚠️ SOLO PARA PRUEBAS:</strong> Este botón cancela la suscripción inmediatamente y quita el acceso premium al instante.
                            Diferente al botón normal que mantiene acceso hasta expiración.
                        </p>
                        <form action="{{ route('subscriptions.force-cancel') }}" method="POST"
                              onsubmit="return confirm('🧪 PRUEBA: ¿Cancelar suscripción y perder acceso inmediato?\n\nEsto es SOLO para pruebas. Perderás acceso premium ahora mismo.');">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                                🧪 Cancelar y Perder Acceso Inmediato (PRUEBA)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cancelar Suscripción -->
                @if($subscription && $subscription->auto_renovacion && $subscription->estado === 'activa')
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-yellow-800 text-lg mb-2">Cancelar Suscripcion</h4>
                            <p class="text-yellow-700 text-sm mb-4">
                                Cancela la renovacion automatica. Mantendras acceso a tu plan hasta el
                                <strong>{{ $subscription->fecha_expiracion->format('d/m/Y') }}</strong>.
                                No se realizaran mas cobros.
                            </p>
                            <form action="{{ route('subscriptions.cancel') }}" method="POST"
                                  onsubmit="return confirm('¿Estas seguro de que quieres cancelar tu suscripcion?\n\nMantendras acceso hasta el {{ $subscription->fecha_expiracion->format('d/m/Y') }} pero no se renovara automaticamente.');">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                                    Cancelar Suscripcion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @elseif($subscription && $subscription->estado === 'cancelada_fin_periodo')
                <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-700 text-lg mb-2">Suscripcion Cancelada</h4>
                            <p class="text-gray-600 text-sm mb-4">
                                Tu suscripcion ya esta cancelada. Tienes acceso hasta el
                                <strong>{{ $subscription->fecha_expiracion->format('d/m/Y') }}</strong>.
                            </p>
                            <form action="{{ route('subscriptions.reactivate') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                                    Reactivar Suscripcion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-600 text-lg mb-2">Sin Suscripcion Activa</h4>
                            <p class="text-gray-500 text-sm mb-4">
                                No tienes ninguna suscripcion activa para cancelar.
                            </p>
                            <a href="{{ route('subscriptions.index') }}"
                               class="block w-full text-center bg-heart-red hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                                Ver Planes
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Darse de Baja (Eliminar Cuenta) -->
                <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-800 text-lg mb-2">Darse de Baja de la Web</h4>
                            <p class="text-red-700 text-sm mb-4">
                                Elimina tu cuenta permanentemente. Se borraran todos tus datos, fotos, matches y conversaciones.
                                <strong>Esta accion no se puede deshacer.</strong>
                            </p>
                            <a href="{{ route('profile.edit') }}#delete-account"
                               class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                                Eliminar mi Cuenta
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Diferencia entre cancelar y darse de baja:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                            <li><strong>Cancelar suscripcion:</strong> Dejas de pagar pero mantienes tu cuenta y acceso hasta fin de periodo.</li>
                            <li><strong>Darse de baja:</strong> Eliminas tu cuenta completamente, incluyendo todos tus datos y conversaciones.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Volver -->
        <div class="text-center mt-8">
            <a href="{{ route('dashboard') }}" class="text-brown hover:text-heart-red font-semibold transition">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-bounce">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-2xl shadow-2xl animate-bounce">
        {{ session('error') }}
    </div>
@endif
@endsection
