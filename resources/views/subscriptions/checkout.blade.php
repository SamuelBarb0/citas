@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center text-brown hover:text-heart-red transition mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Planes
            </a>
            <h1 class="text-4xl font-black text-brown mb-2">
                {{ isset($currentSubscription) && $currentSubscription ? 'Cambiar tu Suscripcion' : 'Finaliza tu Suscripcion' }}
            </h1>
            <p class="text-gray-600">Completa tu pago de forma segura</p>
        </div>

        @if(isset($currentSubscription) && $currentSubscription)
        <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-5 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-bold text-amber-800 mb-1">Cambio de suscripcion</p>
                    <p class="text-sm text-amber-700">
                        Tu suscripcion actual ({{ $currentSubscription->tipo === 'anual' ? 'Anual' : 'Mensual' }})
                        sera cancelada automaticamente al activar la nueva.
                        El cambio es inmediato.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @php
            // Determinar que precio tiene el plan
            $tieneMensual = $plan->precio_mensual > 0;
            $tieneAnual = $plan->precio_anual > 0;

            // Determinar el tipo y precio basado en lo que tiene el plan
            if ($tieneMensual && $tieneAnual) {
                // Plan con ambos precios - usar el tipo que venga o mensual por defecto
                $tipoSuscripcion = $tipo ?? 'mensual';
                $precio = $tipoSuscripcion === 'anual' ? $plan->precio_anual : $plan->precio_mensual;
                $periodo = $tipoSuscripcion === 'anual' ? 'año' : 'mes';
                $mostrarSelector = true;
            } elseif ($tieneMensual) {
                // Solo precio mensual
                $tipoSuscripcion = 'mensual';
                $precio = $plan->precio_mensual;
                $periodo = 'mes';
                $mostrarSelector = false;
            } elseif ($tieneAnual) {
                // Solo precio anual
                $tipoSuscripcion = 'anual';
                $precio = $plan->precio_anual;
                $periodo = 'año';
                $mostrarSelector = false;
            } else {
                // Plan gratuito - no deberia llegar aqui
                $tipoSuscripcion = 'mensual';
                $precio = 0;
                $periodo = 'mes';
                $mostrarSelector = false;
            }
        @endphp

        <!-- Selector de tipo de suscripcion (solo si tiene ambos precios) -->
        @if($mostrarSelector)
        <div class="bg-white rounded-3xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-brown mb-4 text-center">Elige tu periodo de facturacion</h2>
            <div class="grid grid-cols-2 gap-4">
                <!-- Opcion Mensual -->
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_selector" value="mensual" class="hidden peer" {{ $tipoSuscripcion === 'mensual' ? 'checked' : '' }}>
                    <div class="peer-checked:border-heart-red peer-checked:bg-red-50 border-2 border-gray-200 rounded-2xl p-4 transition-all hover:border-heart-red/50">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-1">Mensual</p>
                            <p class="text-3xl font-black text-brown">{{ number_format($plan->precio_mensual, 2) }}€</p>
                            <p class="text-xs text-gray-500">/mes</p>
                        </div>
                    </div>
                </label>

                <!-- Opcion Anual -->
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_selector" value="anual" class="hidden peer" {{ $tipoSuscripcion === 'anual' ? 'checked' : '' }}>
                    <div class="peer-checked:border-heart-red peer-checked:bg-red-50 border-2 border-gray-200 rounded-2xl p-4 transition-all hover:border-heart-red/50 relative">
                        @if($plan->descuento_anual > 0)
                        <div class="absolute -top-2 -right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                            -{{ $plan->descuento_anual }}%
                        </div>
                        @endif
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-1">Anual</p>
                            <p class="text-3xl font-black text-brown">{{ number_format($plan->precio_anual, 2) }}€</p>
                            <p class="text-xs text-gray-500">/año</p>
                            <p class="text-xs text-green-600 font-semibold mt-1">{{ number_format($plan->precio_anual / 12, 2) }}€/mes</p>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        @endif

        <!-- Resumen de la Suscripcion -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6">
            <h2 class="text-2xl font-bold text-brown mb-6">RESUMEN DE TU SUSCRIPCION</h2>

            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <p id="plan-tipo-display" class="text-lg font-semibold text-gray-800">
                            Plan {{ $tipoSuscripcion === 'anual' ? 'anual' : 'mensual' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p id="precio-display" class="text-2xl font-black text-heart-red">
                            {{ number_format($precio, 2) }}€
                        </p>
                        <p id="periodo-display" class="text-sm text-gray-500">
                            /{{ $periodo }}
                        </p>
                        <p class="text-xs text-gray-500">(IVA incluido)</p>
                    </div>
                </div>

                @if($tipoSuscripcion === 'anual' && $precio > 0)
                <div id="ahorro-anual" class="mt-3 bg-green-50 border border-green-200 rounded-xl p-3">
                    <p class="text-sm text-green-700">
                        <span class="font-bold">Solo {{ number_format($precio / 12, 2) }}€ al mes!</span>
                    </p>
                </div>
                @endif
            </div>

            <div class="bg-brown/5 rounded-2xl p-6 mb-6">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-bold text-brown">TOTAL A PAGAR:</p>
                    <p id="total-display" class="text-3xl font-black text-heart-red">
                        {{ number_format($precio, 2) }}€
                    </p>
                </div>
                <p id="renovacion-texto" class="text-xs text-gray-500 mt-2 text-right">
                    Se renovara automaticamente cada {{ $periodo }}
                </p>
            </div>
        </div>

        <!-- Validacion Legal -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6">
            <h3 class="text-xl font-bold text-brown mb-6">VALIDACION LEGAL</h3>

            <form id="payment-form" method="POST" action="{{ route('subscriptions.paypal') }}">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="tipo" id="tipo-hidden" value="{{ $tipoSuscripcion }}">

                <!-- Checkbox 1: Terminos de Contratacion y Condiciones de Pago -->
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input
                            type="checkbox"
                            id="terms-checkbox"
                            name="accept_terms"
                            required
                            class="w-5 h-5 text-heart-red border-gray-300 rounded focus:ring-heart-red focus:ring-2 mt-1 flex-shrink-0"
                        >
                        <span class="text-gray-700 text-sm leading-relaxed">
                            He leido y acepto los
                            <a href="{{ route('legal.contract-terms') }}" target="_blank" class="text-heart-red hover:underline font-semibold">Terminos de Contratacion</a>
                            y las
                            <a href="{{ route('legal.payment-conditions') }}" target="_blank" class="text-heart-red hover:underline font-semibold">Condiciones de Pago</a>.
                            Entiendo que se trata de una suscripcion que se renovara automaticamente.
                        </span>
                    </label>
                </div>

                <!-- Checkbox 2: No Devolucion -->
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input
                            type="checkbox"
                            id="no-refund-checkbox"
                            name="accept_no_refund"
                            required
                            class="w-5 h-5 text-heart-red border-gray-300 rounded focus:ring-heart-red focus:ring-2 mt-1 flex-shrink-0"
                        >
                        <span class="text-gray-700 text-sm leading-relaxed">
                            Entiendo que tendre acceso inmediato al servicio y que, por tanto,
                            <strong class="text-brown">no podre solicitar una devolucion</strong>
                            una vez que haya entrado en mi cuenta.
                        </span>
                    </label>
                </div>

                <!-- Mensaje de Error si faltan checkboxes -->
                <div id="validation-error" class="hidden bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-2 text-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-semibold">Debes aceptar ambas condiciones para continuar con el pago.</p>
                    </div>
                </div>

                <!-- Boton PayPal (deshabilitado inicialmente) -->
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-brown mb-4 text-center">SELECCIONA TU METODO DE PAGO</h3>

                    <!-- PayPal Button Container -->
                    <div id="paypal-button-container" class="opacity-50 pointer-events-none transition-all duration-300 mb-3"></div>

                    <!-- Card Button Container -->
                    <div id="card-button-container" class="opacity-50 pointer-events-none transition-all duration-300"></div>

                    <!-- Iconos de Tarjetas -->
                    <div class="flex justify-center gap-4 mt-6 items-center">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6 opacity-60">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6 opacity-60">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="American Express" class="h-6 opacity-60">
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-6">
                        <div class="flex items-center justify-center gap-2 text-green-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm font-semibold">Pago 100% Seguro. Tus datos estan protegidos.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Informacion Adicional -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
            <p class="text-sm text-blue-900">
                <strong>Importante:</strong> Una vez completado el pago, recibiras un email de confirmacion
                y tendras acceso inmediato a todas las funcionalidades premium de la plataforma.
            </p>
        </div>
    </div>
</div>

<!-- PayPal SDK -->
<script src="{{ config('paypal.sdk_url') }}?client-id={{ config('paypal.client_id') }}&vault=true&intent=subscription&currency={{ config('paypal.currency') }}&locale={{ config('paypal.locale') }}"></script>

<script>
    // Precios del plan
    const precioMensual = {{ $plan->precio_mensual ?? 0 }};
    const precioAnual = {{ $plan->precio_anual ?? 0 }};
    let tipoActual = '{{ $tipoSuscripcion }}';
    const mostrarSelector = {{ $mostrarSelector ? 'true' : 'false' }};

    // Referencias a los checkboxes
    const termsCheckbox = document.getElementById('terms-checkbox');
    const noRefundCheckbox = document.getElementById('no-refund-checkbox');
    const paypalContainer = document.getElementById('paypal-button-container');
    const cardContainer = document.getElementById('card-button-container');
    const validationError = document.getElementById('validation-error');
    const tipoHidden = document.getElementById('tipo-hidden');

    // Referencias para actualizar precios (solo si hay selector)
    const precioDisplay = document.getElementById('precio-display');
    const periodoDisplay = document.getElementById('periodo-display');
    const totalDisplay = document.getElementById('total-display');
    const renovacionTexto = document.getElementById('renovacion-texto');
    const ahorroAnual = document.getElementById('ahorro-anual');
    const planTipoDisplay = document.getElementById('plan-tipo-display');

    // Estado de validacion
    let paymentsEnabled = false;

    // Funcion para actualizar los precios mostrados (solo si hay selector)
    function actualizarPrecios(tipo) {
        if (!mostrarSelector) return;

        tipoActual = tipo;
        tipoHidden.value = tipo;

        const precio = tipo === 'anual' ? precioAnual : precioMensual;
        const periodo = tipo === 'anual' ? '/año' : '/mes';
        const renovacion = tipo === 'anual' ? 'año' : 'mes';

        precioDisplay.textContent = precio.toFixed(2).replace('.', ',') + '€';
        periodoDisplay.textContent = periodo;
        totalDisplay.textContent = precio.toFixed(2).replace('.', ',') + '€';
        renovacionTexto.textContent = 'Se renovara automaticamente cada ' + renovacion;

        if (planTipoDisplay) {
            planTipoDisplay.textContent = 'Plan ' + (tipo === 'anual' ? 'anual' : 'mensual');
        }

        if (ahorroAnual) {
            if (tipo === 'anual') {
                ahorroAnual.classList.remove('hidden');
            } else {
                ahorroAnual.classList.add('hidden');
            }
        }
    }

    // Listeners para selector de tipo (si existe)
    if (mostrarSelector) {
        const tipoSelectors = document.querySelectorAll('input[name="tipo_selector"]');
        tipoSelectors.forEach(radio => {
            radio.addEventListener('change', function() {
                actualizarPrecios(this.value);
            });
        });
    }

    // Funcion para verificar si ambos checkboxes estan marcados
    function checkValidation() {
        const bothChecked = termsCheckbox.checked && noRefundCheckbox.checked;

        if (bothChecked && !paymentsEnabled) {
            paymentsEnabled = true;
            paypalContainer.classList.remove('opacity-50', 'pointer-events-none');
            cardContainer.classList.remove('opacity-50', 'pointer-events-none');
            validationError.classList.add('hidden');
        } else if (!bothChecked && paymentsEnabled) {
            paymentsEnabled = false;
            paypalContainer.classList.add('opacity-50', 'pointer-events-none');
            cardContainer.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    // Listeners para los checkboxes
    termsCheckbox.addEventListener('change', checkValidation);
    noRefundCheckbox.addEventListener('change', checkValidation);

    // Funcion para mostrar error si se intenta pagar sin aceptar
    function showValidationError() {
        if (!paymentsEnabled) {
            validationError.classList.remove('hidden');
            validationError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return true;
        }
        return false;
    }

    // Configuracion comun para crear suscripcion
    function createSubscriptionHandler(data, actions) {
        if (showValidationError()) {
            return Promise.reject(new Error('Validation failed'));
        }

        return fetch('{{ route("subscriptions.paypal.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                plan_id: '{{ $plan->id }}',
                tipo: tipoActual
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.subscription_id) {
                return data.subscription_id;
            } else {
                throw new Error(data.message || 'Error al crear la suscripcion');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la suscripcion: ' + error.message);
            throw error;
        });
    }

    function onApproveHandler(data, actions) {
        const successUrl = '{{ route("subscriptions.paypal.success") }}' +
            '?subscription_id=' + data.subscriptionID +
            '&plan_id={{ $plan->id }}' +
            '&tipo=' + tipoActual;

        window.location.href = successUrl;
    }

    function onErrorHandler(err) {
        console.error('Error en PayPal:', err);
        if (!paymentsEnabled) {
            showValidationError();
        } else {
            alert('Ocurrio un error al procesar el pago. Por favor, intentalo de nuevo.');
        }
    }

    // PayPal Button
    paypal.Buttons({
        fundingSource: paypal.FUNDING.PAYPAL,
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'rect',
            label: 'subscribe',
            height: 50
        },
        createSubscription: createSubscriptionHandler,
        onApprove: onApproveHandler,
        onCancel: function(data) {
            console.log('Pago cancelado por el usuario');
        },
        onError: onErrorHandler
    }).render('#paypal-button-container');

    // Card Button (Debit/Credit)
    paypal.Buttons({
        fundingSource: paypal.FUNDING.CARD,
        style: {
            layout: 'vertical',
            color: 'black',
            shape: 'rect',
            label: 'pay',
            height: 50
        },
        createSubscription: createSubscriptionHandler,
        onApprove: onApproveHandler,
        onCancel: function(data) {
            console.log('Pago cancelado por el usuario');
        },
        onError: onErrorHandler
    }).render('#card-button-container');

    // Verificacion inicial al cargar la pagina
    checkValidation();
</script>
@endsection
