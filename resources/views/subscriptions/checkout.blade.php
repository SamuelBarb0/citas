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
            <h1 class="text-4xl font-black text-brown mb-2">Finaliza tu Suscripción</h1>
            <p class="text-gray-600">Completa tu pago de forma segura</p>
        </div>

        <!-- Resumen de la Suscripción -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6">
            <h2 class="text-2xl font-bold text-brown mb-6">RESUMEN DE TU SUSCRIPCIÓN</h2>

            @php
                // El tipo se pasa desde el controlador, si no existe lo determinamos por el slug del plan
                $tipoSuscripcion = $tipo ?? ($plan->slug === 'anual' ? 'anual' : 'mensual');

                // Determinar precio según el tipo
                $precio = $tipoSuscripcion === 'anual' ? $plan->precio_anual : $plan->precio_mensual;
                $periodo = ucfirst($tipoSuscripcion);
            @endphp

            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <p class="text-lg font-semibold text-gray-800">Plan: {{ $plan->nombre }}</p>
                        <p class="text-sm text-gray-600">{{ $plan->descripcion }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-heart-red">{{ number_format($precio, 2) }}€</p>
                        <p class="text-xs text-gray-500">(IVA incluido)</p>
                    </div>
                </div>

                @if($plan->slug === 'anual')
                    <div class="mt-3 bg-green-50 border border-green-200 rounded-xl p-3">
                        <p class="text-sm text-green-700">
                            <span class="font-bold">💰 ¡Solo {{ number_format($precio / 12, 2) }}€ al mes!</span>
                        </p>
                    </div>
                @endif
            </div>

            <div class="bg-brown/5 rounded-2xl p-6 mb-6">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-bold text-brown">TOTAL A PAGAR:</p>
                    <p class="text-3xl font-black text-heart-red">{{ number_format($precio, 2) }}€</p>
                </div>
            </div>
        </div>

        <!-- Validación Legal -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6">
            <h3 class="text-xl font-bold text-brown mb-6">VALIDACIÓN LEGAL</h3>

            <form id="payment-form" method="POST" action="{{ route('subscriptions.paypal') }}">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="tipo" value="{{ $tipoSuscripcion }}">

                <!-- Checkbox 1: Términos de Contratación y Condiciones de Pago -->
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
                            He leído y acepto los
                            <a href="{{ route('legal.contract-terms') }}" target="_blank" class="text-heart-red hover:underline font-semibold">Términos de Contratación</a>
                            y las
                            <a href="{{ route('legal.payment-conditions') }}" target="_blank" class="text-heart-red hover:underline font-semibold">Condiciones de Pago</a>.
                            Entiendo que se trata de una suscripción que se renovará automáticamente.
                        </span>
                    </label>
                </div>

                <!-- Checkbox 2: No Devolución -->
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
                            Entiendo que tendré acceso inmediato al servicio y que, por tanto,
                            <strong class="text-brown">no podré solicitar una devolución</strong>
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

                <!-- Botón PayPal (deshabilitado inicialmente) -->
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-brown mb-4 text-center">SELECCIONA TU MÉTODO DE PAGO</h3>

                    <!-- PayPal Button Container -->
                    <div id="paypal-button-container" class="opacity-50 pointer-events-none transition-all duration-300"></div>

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
                            <p class="text-sm font-semibold">Pago 100% Seguro. Tus datos están protegidos.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Información Adicional -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
            <p class="text-sm text-blue-900">
                <strong>ℹ️ Importante:</strong> Una vez completado el pago, recibirás un email de confirmación
                y tendrás acceso inmediato a todas las funcionalidades premium de la plataforma.
            </p>
        </div>
    </div>
</div>

<!-- PayPal SDK -->
<script src="{{ config('paypal.sdk_url') }}?client-id={{ config('paypal.client_id') }}&vault=true&intent=subscription&currency={{ config('paypal.currency') }}&locale={{ config('paypal.locale') }}"></script>

<script>
    // Referencias a los checkboxes
    const termsCheckbox = document.getElementById('terms-checkbox');
    const noRefundCheckbox = document.getElementById('no-refund-checkbox');
    const paypalContainer = document.getElementById('paypal-button-container');
    const validationError = document.getElementById('validation-error');

    // Estado de validación
    let paymentsEnabled = false;

    // Función para verificar si ambos checkboxes están marcados
    function checkValidation() {
        const bothChecked = termsCheckbox.checked && noRefundCheckbox.checked;

        if (bothChecked && !paymentsEnabled) {
            // Habilitar pagos
            paymentsEnabled = true;
            paypalContainer.classList.remove('opacity-50', 'pointer-events-none');
            validationError.classList.add('hidden');
        } else if (!bothChecked && paymentsEnabled) {
            // Deshabilitar pagos
            paymentsEnabled = false;
            paypalContainer.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    // Listeners para los checkboxes
    termsCheckbox.addEventListener('change', checkValidation);
    noRefundCheckbox.addEventListener('change', checkValidation);

    // Función para mostrar error si se intenta pagar sin aceptar
    function showValidationError() {
        if (!paymentsEnabled) {
            validationError.classList.remove('hidden');
            // Scroll al error
            validationError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return true;
        }
        return false;
    }

    // PayPal Button Setup
    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'rect',
            label: 'subscribe',
            height: 50
        },

        createSubscription: function(data, actions) {
            // Verificar validación antes de crear suscripción
            if (showValidationError()) {
                return Promise.reject(new Error('Validation failed'));
            }

            // Crear suscripción en PayPal
            return fetch('{{ route("subscriptions.paypal.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    plan_id: '{{ $plan->id }}',
                    tipo: '{{ $tipoSuscripcion }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.subscription_id) {
                    return data.subscription_id;
                } else {
                    throw new Error(data.message || 'Error al crear la suscripción');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la suscripción: ' + error.message);
                throw error;
            });
        },

        onApprove: function(data, actions) {
            // Suscripción aprobada - redirigir a página de éxito que activará la suscripción
            const successUrl = '{{ route("subscriptions.paypal.success") }}' +
                '?subscription_id=' + data.subscriptionID +
                '&plan_id={{ $plan->id }}' +
                '&tipo={{ $tipoSuscripcion }}';

            window.location.href = successUrl;
        },

        onCancel: function(data) {
            // Usuario canceló el pago
            console.log('Pago cancelado por el usuario');
        },

        onError: function(err) {
            // Error durante el proceso
            console.error('Error en PayPal:', err);
            if (!paymentsEnabled) {
                showValidationError();
            } else {
                alert('Ocurrió un error al procesar el pago. Por favor, inténtalo de nuevo.');
            }
        }
    }).render('#paypal-button-container');

    // Verificación inicial al cargar la página
    checkValidation();
</script>
@endsection
