@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header -->
    <div class="bg-gradient-to-r from-heart-red to-heart-red-light shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-3">Mejora tu Experiencia</h1>
                <p class="text-white/90 text-lg">Elige el plan perfecto para encontrar tu match ideal</p>
            </div>
        </div>
    </div>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Toggle Mensual/Anual -->
            <div class="flex justify-center mb-12">
                <div class="bg-white rounded-full p-2 shadow-lg inline-flex" x-data="{ tipo: 'mensual' }">
                    <button
                        @click="tipo = 'mensual'"
                        :class="tipo === 'mensual' ? 'bg-heart-red text-white' : 'text-gray-600'"
                        class="px-8 py-3 rounded-full font-bold transition text-sm md:text-base"
                    >
                        Mensual
                    </button>
                    <button
                        @click="tipo = 'anual'"
                        :class="tipo === 'anual' ? 'bg-heart-red text-white' : 'text-gray-600'"
                        class="px-8 py-3 rounded-full font-bold transition text-sm md:text-base relative"
                    >
                        Anual
                        <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                            Ahorra
                        </span>
                    </button>
                </div>
            </div>

            <!-- Planes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" x-data="{ tipo: 'mensual' }">
                @foreach($plans as $plan)
                    <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300
                        {{ $plan->slug === 'premium' ? 'border-4 border-heart-red scale-105' : '' }}">

                        <!-- Badge Recomendado -->
                        @if($plan->slug === 'premium')
                            <div class="absolute top-0 right-0 bg-heart-red text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                ⭐ Más Popular
                            </div>
                        @endif

                        <!-- Badge Gratis -->
                        @if($plan->isFree())
                            <div class="absolute top-0 right-0 bg-green-500 text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                ✓ Gratis
                            </div>
                        @endif

                        <!-- Contenido del Plan -->
                        <div class="p-8">
                            <!-- Nombre del Plan -->
                            <h3 class="text-3xl font-black text-brown mb-2">{{ $plan->nombre }}</h3>
                            <p class="text-gray-600 mb-6 text-sm">{{ $plan->descripcion }}</p>

                            <!-- Precio -->
                            @if($plan->isFree())
                                <div class="mb-8">
                                    <div class="text-5xl font-black text-heart-red">Gratis</div>
                                    <p class="text-gray-500 text-sm mt-2">Para siempre</p>
                                </div>
                            @else
                                <div class="mb-8">
                                    <!-- Precio Mensual -->
                                    <div x-show="tipo === 'mensual'" class="transition">
                                        <div class="text-5xl font-black text-heart-red">
                                            €{{ number_format($plan->precio_mensual, 2) }}
                                        </div>
                                        <p class="text-gray-500 text-sm mt-2">por mes</p>
                                    </div>

                                    <!-- Precio Anual -->
                                    <div x-show="tipo === 'anual'" class="transition">
                                        <div class="flex items-baseline gap-3">
                                            <div class="text-5xl font-black text-heart-red">
                                                €{{ number_format($plan->precio_anual / 12, 2) }}
                                            </div>
                                            <div class="text-gray-400 line-through text-xl">
                                                €{{ number_format($plan->precio_mensual, 2) }}
                                            </div>
                                        </div>
                                        <p class="text-gray-500 text-sm mt-2">
                                            por mes (€{{ number_format($plan->precio_anual, 2) }}/año)
                                        </p>
                                        <div class="inline-block mt-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                            Ahorras {{ $plan->descuento_anual }}% (€{{ number_format($plan->ahorro_anual, 2) }})
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Características -->
                            <ul class="space-y-4 mb-8">
                                <!-- Likes -->
                                <li class="flex items-start gap-3">
                                    <span class="text-heart-red text-xl flex-shrink-0">❤️</span>
                                    <span class="text-gray-700 text-sm font-semibold">Likes ilimitados</span>
                                </li>

                                <!-- Matches -->
                                <li class="flex items-start gap-3">
                                    <span class="text-purple-500 text-xl flex-shrink-0">💕</span>
                                    <span class="text-gray-700 text-sm font-semibold">Matches ilimitados</span>
                                </li>

                                <!-- Mensajería - PRINCIPAL DIFERENCIA -->
                                @if($plan->slug === 'free')
                                    <li class="flex items-start gap-3">
                                        <span class="text-red-500 text-xl flex-shrink-0">✕</span>
                                        <span class="text-gray-700 text-sm">
                                            <strong class="text-red-600">No puedes iniciar conversaciones</strong><br>
                                            <span class="text-xs text-gray-500">Solo puedes responder mensajes</span>
                                        </span>
                                    </li>
                                @elseif($plan->slug === 'basico')
                                    <li class="flex items-start gap-3">
                                        <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                        <span class="text-gray-700 text-sm">
                                            <strong class="text-green-600">3 mensajes/semana</strong> a usuarios Gratis<br>
                                            <span class="text-xs text-gray-500">Ilimitados entre Básico y Premium</span>
                                        </span>
                                    </li>
                                @elseif($plan->slug === 'premium')
                                    <li class="flex items-start gap-3">
                                        <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                        <span class="text-gray-700 text-sm">
                                            <strong class="text-green-600">Mensajes ilimitados</strong><br>
                                            <span class="text-xs text-gray-500">Con todos los usuarios</span>
                                        </span>
                                    </li>
                                @endif

                                <!-- Fotos -->
                                <li class="flex items-start gap-3">
                                    <span class="text-blue-500 text-xl flex-shrink-0">📸</span>
                                    <span class="text-gray-700 text-sm">Hasta {{ $plan->fotos_adicionales }} fotos</span>
                                </li>
                            </ul>

                            <!-- Botón CTA -->
                            @if($currentSubscription && $currentSubscription->plan_id === $plan->id)
                                <div class="bg-green-100 text-green-700 py-4 px-6 rounded-2xl font-bold text-center">
                                    ✓ Plan Actual
                                </div>
                            @elseif($plan->isFree())
                                <div class="bg-gray-100 text-gray-600 py-4 px-6 rounded-2xl font-bold text-center">
                                    Plan Básico
                                </div>
                            @else
                                <a :href="`{{ route('subscriptions.checkout', $plan->slug) }}?tipo=${tipo}`"
                                   class="block w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-2xl font-bold text-center hover:shadow-glow transition">
                                    {{ $currentSubscription ? 'Cambiar Plan' : 'Elegir ' . $plan->nombre }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Garantía y FAQ -->
            <div class="mt-16 text-center">
                <div class="bg-white rounded-3xl shadow-lg p-8 max-w-3xl mx-auto">
                    <h3 class="text-2xl font-black text-brown mb-4">🔒 Pago 100% Seguro</h3>
                    <p class="text-gray-600 mb-6">
                        Aceptamos pagos mediante Stripe y PayPal. Todos los pagos están encriptados y protegidos.
                        Cancela cuando quieras, sin compromisos.
                    </p>
                    <div class="flex justify-center gap-6 flex-wrap">
                        <img src="https://stripe.com/img/v3/home/social.png" alt="Stripe" class="h-8 opacity-60">
                        <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_74x46.jpg" alt="PayPal" class="h-8 opacity-60">
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
</div>

<!-- Alpine.js ya está incluido en el layout -->
@endsection
