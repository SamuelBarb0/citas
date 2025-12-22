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
                                <p class="text-white/70 text-sm mb-1">Días Restantes</p>
                                <p class="font-bold">{{ $subscription->dias_restantes }} días</p>
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

                        @if($subscription->auto_renovacion)
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
                        @else
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
                        @endif
                    </div>

                    <!-- Super Likes -->
                    @if($subscription->plan->super_likes_mes > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="font-bold text-brown mb-4 flex items-center gap-2">
                                <span class="text-2xl">⭐</span>
                                Super Likes
                            </h3>
                            <p class="text-3xl font-black text-blue-500 mb-2">{{ $subscription->super_likes_restantes }}</p>
                            <p class="text-gray-600 text-sm">Disponibles este mes</p>
                        </div>
                    @endif

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
                            @if($subscription->plan->ver_quien_te_gusta)
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ver quién te gusta
                                </div>
                            @endif
                            @if($subscription->plan->rewind)
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Deshacer swipes
                                </div>
                            @endif
                            @if($subscription->plan->sin_anuncios)
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Sin anuncios
                                </div>
                            @endif
                            @if($subscription->plan->modo_incognito)
                                <div class="flex items-center gap-2 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Modo incógnito
                                </div>
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
