@extends(Auth::check() ? 'layouts.app' : 'layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream">
    <!-- Header -->
    <div class="bg-gradient-to-r from-heart-red to-heart-red-light shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-3">Encuentra tu Match Perfecto</h1>
                <p class="text-white/90 text-lg">Elige el plan que mejor se adapte a ti</p>
            </div>
        </div>
    </div>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Planes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @foreach($plans as $index => $plan)
                    <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300
                        {{ $index === 1 ? 'border-4 border-heart-red md:scale-105 z-10' : '' }}">

                        <!-- Badge Recomendado (segundo plan) -->
                        @if($index === 1)
                            <div class="absolute top-0 right-0 bg-heart-red text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                Recomendado
                            </div>
                        @endif

                        <!-- Badge Gratis -->
                        @if($plan->isFree())
                            <div class="absolute top-0 right-0 bg-green-500 text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                Gratis
                            </div>
                        @endif

                        <!-- Badge Ahorro -->
                        @if($plan->precio_anual && $plan->descuento_anual > 0 && $index !== 1)
                            <div class="absolute top-0 right-0 bg-green-500 text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                Ahorra {{ $plan->descuento_anual }}%
                            </div>
                        @endif

                        <!-- Contenido del Plan -->
                        <div class="p-8">
                            <!-- Nombre del Plan -->
                            <h3 class="text-3xl font-black text-brown mb-6">{{ $plan->nombre }}</h3>

                            <!-- Precio -->
                            <div class="mb-8 min-h-[100px] flex flex-col justify-center">
                                @if($plan->isFree())
                                    <div class="text-5xl font-black text-heart-red">0€</div>
                                    <p class="text-gray-500 text-sm mt-2">Para siempre</p>
                                @elseif($plan->precio_mensual > 0 && $plan->precio_anual > 0)
                                    {{-- Tiene ambos precios: mostrar mensual como principal --}}
                                    <div class="text-5xl font-black text-heart-red">
                                        {{ number_format($plan->precio_mensual, 2) }}€
                                    </div>
                                    <p class="text-gray-500 text-lg mt-2">/mes</p>
                                    <p class="text-xs text-gray-400 mt-1">(IVA incluido)</p>
                                    <div class="mt-3 bg-green-50 border border-green-200 rounded-xl p-2">
                                        <p class="text-xs text-green-700 font-semibold">
                                            O {{ number_format($plan->precio_anual, 2) }}€/año ({{ number_format($plan->precio_anual / 12, 2) }}€/mes)
                                        </p>
                                    </div>
                                @elseif($plan->precio_mensual > 0)
                                    {{-- Solo precio mensual --}}
                                    <div class="text-5xl font-black text-heart-red">
                                        {{ number_format($plan->precio_mensual, 2) }}€
                                    </div>
                                    <p class="text-gray-500 text-lg mt-2">/mes</p>
                                    <p class="text-xs text-gray-400 mt-1">(IVA incluido)</p>
                                @elseif($plan->precio_anual > 0)
                                    {{-- Solo precio anual --}}
                                    <div class="text-5xl font-black text-heart-red">
                                        {{ number_format($plan->precio_anual, 2) }}€
                                    </div>
                                    <p class="text-gray-500 text-lg mt-2">/año</p>
                                    <p class="text-xs text-gray-400 mt-1">(IVA incluido)</p>
                                    <div class="mt-3 bg-green-50 border border-green-200 rounded-xl p-2">
                                        <p class="text-xs text-green-700 font-semibold">
                                            Solo {{ number_format($plan->precio_anual / 12, 2) }}€/mes
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Caracteristicas -->
                            <div class="border-t pt-6 mb-8">
                                <h4 class="font-bold text-brown mb-4 text-sm uppercase tracking-wide">¿Que incluye?</h4>
                                <ul class="space-y-3">
                                    <!-- Caracteristicas personalizadas del plan -->
                                    @if($plan->caracteristicas_personalizadas && count($plan->caracteristicas_personalizadas) > 0)
                                        @foreach($plan->caracteristicas_personalizadas as $caracteristica)
                                            <li class="flex items-start gap-3">
                                                <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                                <span class="text-gray-700 text-sm">{{ $caracteristica }}</span>
                                            </li>
                                        @endforeach
                                    @endif

                                    <!-- Caracteristicas del sistema basadas en configuracion -->
                                    @if($plan->likes_diarios === 0 || $plan->likes_diarios === null)
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Likes ilimitados</span>
                                        </li>
                                    @elseif($plan->likes_diarios > 0)
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">{{ $plan->likes_diarios }} likes diarios</span>
                                        </li>
                                    @endif

                                    @if($plan->puede_iniciar_conversacion)
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm font-bold">Iniciar conversaciones</span>
                                        </li>
                                    @else
                                        <li class="flex items-start gap-3">
                                            <span class="text-red-500 text-xl flex-shrink-0">✗</span>
                                            <span class="text-gray-500 text-sm line-through">Iniciar conversaciones</span>
                                        </li>
                                    @endif

                                    @if($plan->mensajes_ilimitados)
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm font-bold">Mensajes ilimitados</span>
                                        </li>
                                    @elseif($plan->mensajes_semanales_gratis > 0)
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">{{ $plan->mensajes_semanales_gratis }} mensajes gratis/semana</span>
                                        </li>
                                    @else
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Puedes responder mensajes</span>
                                        </li>
                                    @endif

                                    @if($plan->ver_quien_te_gusta)
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Ver quien te ha dado like</span>
                                        </li>
                                    @endif

                                </ul>
                            </div>

                            <!-- Boton CTA -->
                            @if($currentSubscription && $currentSubscription->plan_id === $plan->id)
                                <div class="bg-green-100 text-green-700 py-4 px-6 rounded-2xl font-bold text-center mb-2">
                                    Tu Plan Actual ({{ $currentSubscription->tipo === 'anual' ? 'Anual' : 'Mensual' }})
                                </div>
                                @if($plan->precio_mensual > 0 && $plan->precio_anual > 0)
                                    @if($currentSubscription->tipo === 'mensual')
                                        <a href="{{ route('subscriptions.checkout', ['planSlug' => $plan->slug, 'tipo' => 'anual']) }}"
                                           class="block w-full bg-brown text-white py-3 px-6 rounded-2xl font-bold text-center text-sm hover:bg-brown-dark transition">
                                            Cambiar a Anual ({{ number_format($plan->precio_anual, 2) }}€/año)
                                        </a>
                                    @else
                                        <a href="{{ route('subscriptions.checkout', ['planSlug' => $plan->slug, 'tipo' => 'mensual']) }}"
                                           class="block w-full bg-brown text-white py-3 px-6 rounded-2xl font-bold text-center text-sm hover:bg-brown-dark transition">
                                            Cambiar a Mensual ({{ number_format($plan->precio_mensual, 2) }}€/mes)
                                        </a>
                                    @endif
                                @endif
                            @elseif($plan->isFree())
                                @if(Auth::check())
                                    <div class="bg-gray-100 text-gray-600 py-4 px-6 rounded-2xl font-bold text-center">
                                        Plan Gratuito
                                    </div>
                                @else
                                    <a href="{{ route('register') }}"
                                       class="block w-full bg-brown text-white py-4 px-6 rounded-2xl font-bold text-center hover:bg-brown-dark transition">
                                        Registrarse Gratis
                                    </a>
                                @endif
                            @else
                                @if(Auth::check())
                                    @php
                                        if ($currentSubscription) {
                                            // Ya tiene suscripcion, mostrar "Cambiar"
                                            if ($plan->precio_mensual > 0) {
                                                $btnText = 'Cambiar a este plan (' . number_format($plan->precio_mensual, 2) . '€/mes)';
                                            } elseif ($plan->precio_anual > 0) {
                                                $btnText = 'Cambiar a este plan (' . number_format($plan->precio_anual, 2) . '€/año)';
                                            } else {
                                                $btnText = 'Cambiar a este plan';
                                            }
                                        } else {
                                            if ($plan->precio_mensual > 0) {
                                                $btnText = 'Suscribirse (' . number_format($plan->precio_mensual, 2) . '€/mes)';
                                            } elseif ($plan->precio_anual > 0) {
                                                $btnText = 'Suscribirse (' . number_format($plan->precio_anual, 2) . '€/año)';
                                            } else {
                                                $btnText = 'Suscribirse';
                                            }
                                        }
                                    @endphp
                                    <a href="{{ route('subscriptions.checkout', $plan->slug) }}"
                                       class="block w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-2xl font-bold text-center hover:shadow-glow transition">
                                        {{ $btnText }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="block w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-2xl font-bold text-center hover:shadow-glow transition">
                                        Iniciar sesion para suscribirse
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($plans->count() === 0)
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No hay planes disponibles en este momento.</p>
                </div>
            @endif

            <!-- Informacion adicional sobre condiciones -->
            <div class="bg-white rounded-3xl shadow-lg p-8 max-w-4xl mx-auto mb-12">
                <h3 class="text-2xl font-black text-brown mb-6 text-center">Informacion de Pago</h3>

                <div class="space-y-4 text-gray-700 mb-6">
                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">🔄</span>
                        <div>
                            <p class="font-semibold">Suscripcion Recurrente</p>
                            <p class="text-sm text-gray-600">Tu suscripcion se renovara automaticamente cada mes o año hasta que decidas cancelarla.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">⚡</span>
                        <div>
                            <p class="font-semibold">Acceso Inmediato</p>
                            <p class="text-sm text-gray-600">Tendras acceso completo a todas las funcionalidades inmediatamente despues del pago.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">❌</span>
                        <div>
                            <p class="font-semibold">Politica de No Devolucion</p>
                            <p class="text-sm text-gray-600">Una vez realizado el pago y activado el acceso, no se permiten devoluciones segun la normativa europea de servicios digitales.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">🚫</span>
                        <div>
                            <p class="font-semibold">Cancelacion Flexible</p>
                            <p class="text-sm text-gray-600">Puedes cancelar tu suscripcion en cualquier momento desde tu perfil. Seguiras teniendo acceso hasta el final del periodo ya pagado.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <p class="text-sm text-gray-600 text-center mb-4">
                        Al continuar con el pago, aceptas nuestras
                        <a href="{{ route('legal.payment-conditions') }}" class="text-heart-red hover:underline font-semibold">Condiciones de Pago y Cancelacion</a>
                    </p>
                </div>
            </div>

            <!-- Garantia y Seguridad -->
            <div class="bg-white rounded-3xl shadow-lg p-8 max-w-3xl mx-auto mb-8">
                <h3 class="text-2xl font-black text-brown mb-4 text-center">Pago 100% Seguro</h3>
                <p class="text-gray-600 mb-6 text-center">
                    Tus datos estan protegidos. Utilizamos PayPal para procesar pagos de forma segura.<br>
                    Todos los pagos estan encriptados y protegidos.
                </p>
                <div class="flex justify-center gap-6 flex-wrap items-center">
                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_74x46.jpg" alt="PayPal" class="h-10 opacity-60">
                    <div class="flex gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6 opacity-60">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6 opacity-60">
                    </div>
                </div>
            </div>

            <!-- Boton Volver -->
            <div class="text-center mt-8">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="text-brown hover:text-heart-red font-semibold transition inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Volver al Dashboard
                    </a>
                @else
                    <a href="{{ url('/') }}" class="text-brown hover:text-heart-red font-semibold transition inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Volver al Inicio
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
