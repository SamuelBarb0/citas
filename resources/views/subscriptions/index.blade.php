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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                @php
                    $activePlans = $plans->where('activo', true)->sortBy('orden');
                @endphp

                @foreach($activePlans as $plan)
                    @php
                        // Determinar el precio a mostrar
                        if ($plan->slug === 'free') {
                            $precio = 0;
                            $periodo = '';
                        } elseif ($plan->slug === 'mensual') {
                            $precio = $plan->precio_mensual;
                            $periodo = '/mes';
                        } elseif ($plan->slug === 'anual') {
                            $precio = $plan->precio_anual;
                            $periodo = '/año';
                            $ahorroMensual = number_format(($plan->precio_anual / 12), 2);
                        }
                    @endphp

                    <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300
                        {{ $plan->slug === 'mensual' ? 'border-4 border-heart-red md:scale-105' : '' }}">

                        <!-- Badge Recomendado -->
                        @if($plan->slug === 'mensual')
                            <div class="absolute top-0 right-0 bg-heart-red text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                ⭐ Recomendado
                            </div>
                        @endif

                        <!-- Badge Gratis -->
                        @if($plan->isFree())
                            <div class="absolute top-0 right-0 bg-green-500 text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                ✓ Gratis
                            </div>
                        @endif

                        <!-- Badge Ahorro (Plan Anual) -->
                        @if($plan->slug === 'anual')
                            <div class="absolute top-0 right-0 bg-green-500 text-white px-6 py-2 rounded-bl-3xl font-bold text-sm shadow-lg z-10">
                                💰 Mejor Precio
                            </div>
                        @endif

                        <!-- Contenido del Plan -->
                        <div class="p-8">
                            <!-- Nombre del Plan -->
                            <h3 class="text-3xl font-black text-brown mb-2">{{ $plan->nombre }}</h3>
                            <p class="text-gray-600 mb-6 text-sm min-h-[40px]">{{ $plan->descripcion }}</p>

                            <!-- Precio -->
                            <div class="mb-8 min-h-[120px] flex flex-col justify-center">
                                @if($plan->isFree())
                                    <div class="text-5xl font-black text-heart-red">0€</div>
                                    <p class="text-gray-500 text-sm mt-2">Para siempre</p>
                                @else
                                    <div class="text-5xl font-black text-heart-red">
                                        {{ number_format($precio, 2) }}€
                                    </div>
                                    <p class="text-gray-500 text-lg mt-2">{{ $periodo }}</p>
                                    <p class="text-xs text-gray-400 mt-1">(IVA incluido)</p>

                                    @if($plan->slug === 'anual')
                                        <div class="mt-3 bg-green-50 border border-green-200 rounded-xl p-2">
                                            <p class="text-xs text-green-700 font-semibold">
                                                Solo {{ $ahorroMensual }}€/mes
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Características -->
                            <div class="border-t pt-6 mb-8">
                                <h4 class="font-bold text-brown mb-4 text-sm uppercase tracking-wide">¿Qué incluye?</h4>
                                <ul class="space-y-3">
                                    @if($plan->isFree())
                                        <!-- Plan Gratis -->
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Ver perfiles de otros usuarios</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Dar likes ilimitados</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Hacer matches</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-red-500 text-xl flex-shrink-0">✗</span>
                                            <span class="text-gray-500 text-sm line-through">Iniciar conversaciones</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-yellow-500 text-xl flex-shrink-0">⚠</span>
                                            <span class="text-gray-700 text-sm">Solo puedes responder mensajes</span>
                                        </li>
                                    @else
                                        <!-- Plan de Pago (Mensual o Anual) -->
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Ver perfiles de otros usuarios</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Dar likes ilimitados</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Hacer matches ilimitados</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm font-bold">Iniciar conversaciones</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm font-bold">Mensajes ilimitados</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Ver quién te ha dado like</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
                                            <span class="text-gray-700 text-sm">Hasta {{ $plan->fotos_adicionales }} fotos en tu perfil</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <!-- Botón CTA -->
                            @if($currentSubscription && $currentSubscription->plan_id === $plan->id)
                                <div class="bg-green-100 text-green-700 py-4 px-6 rounded-2xl font-bold text-center">
                                    ✓ Tu Plan Actual
                                </div>
                            @elseif($plan->isFree())
                                <div class="bg-gray-100 text-gray-600 py-4 px-6 rounded-2xl font-bold text-center">
                                    Plan Actual
                                </div>
                            @else
                                <a href="{{ route('subscriptions.checkout', $plan->slug) }}"
                                   class="block w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-2xl font-bold text-center hover:shadow-glow transition">
                                    @if($plan->slug === 'anual')
                                        Suscribirse ({{ number_format($precio, 2) }}€/año)
                                    @else
                                        Suscribirse ({{ number_format($precio, 2) }}€/mes)
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Información adicional sobre condiciones -->
            <div class="bg-white rounded-3xl shadow-lg p-8 max-w-4xl mx-auto mb-12">
                <h3 class="text-2xl font-black text-brown mb-6 text-center">💳 Información de Pago</h3>

                <div class="space-y-4 text-gray-700 mb-6">
                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">🔄</span>
                        <div>
                            <p class="font-semibold">Suscripción Recurrente</p>
                            <p class="text-sm text-gray-600">Tu suscripción se renovará automáticamente cada mes o año hasta que decidas cancelarla.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">⚡</span>
                        <div>
                            <p class="font-semibold">Acceso Inmediato</p>
                            <p class="text-sm text-gray-600">Tendrás acceso completo a todas las funcionalidades inmediatamente después del pago.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">❌</span>
                        <div>
                            <p class="font-semibold">Política de No Devolución</p>
                            <p class="text-sm text-gray-600">Una vez realizado el pago y activado el acceso, no se permiten devoluciones según la normativa europea de servicios digitales.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span class="text-heart-red text-xl flex-shrink-0">🚫</span>
                        <div>
                            <p class="font-semibold">Cancelación Flexible</p>
                            <p class="text-sm text-gray-600">Puedes cancelar tu suscripción en cualquier momento desde tu perfil. Seguirás teniendo acceso hasta el final del período ya pagado.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <p class="text-sm text-gray-600 text-center mb-4">
                        Al continuar con el pago, aceptas nuestras
                        <a href="{{ route('legal.payment-conditions') }}" class="text-heart-red hover:underline font-semibold">Condiciones de Pago y Cancelación</a>
                    </p>
                </div>
            </div>

            <!-- Garantía y Seguridad -->
            <div class="bg-white rounded-3xl shadow-lg p-8 max-w-3xl mx-auto mb-8">
                <h3 class="text-2xl font-black text-brown mb-4 text-center">🔒 Pago 100% Seguro</h3>
                <p class="text-gray-600 mb-6 text-center">
                    Tus datos están protegidos. Utilizamos PayPal para procesar pagos de forma segura.<br>
                    Todos los pagos están encriptados y protegidos.
                </p>
                <div class="flex justify-center gap-6 flex-wrap items-center">
                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_74x46.jpg" alt="PayPal" class="h-10 opacity-60">
                    <div class="flex gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6 opacity-60">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6 opacity-60">
                    </div>
                </div>
            </div>

            <!-- Botón Volver -->
            <div class="text-center mt-8">
                <a href="{{ route('dashboard') }}" class="text-brown hover:text-heart-red font-semibold transition inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
