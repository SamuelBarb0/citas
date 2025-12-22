@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center text-brown hover:text-heart-red transition mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Planes
            </a>
            <h1 class="text-4xl font-black text-brown mb-2">Finalizar Compra</h1>
            <p class="text-gray-600">Completa tu suscripción de forma segura</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Resumen del Plan -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-brown mb-4">Resumen de Compra</h3>

                    <div class="bg-gradient-to-r from-heart-red to-heart-red-light rounded-2xl p-6 mb-6 text-white">
                        <h4 class="text-2xl font-black mb-2">{{ $plan->nombre }}</h4>
                        <p class="text-white/80 text-sm mb-4">{{ $plan->descripcion }}</p>

                        <div class="border-t border-white/30 pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-white/80">Período:</span>
                                <span class="font-bold">{{ $tipo === 'mensual' ? 'Mensual' : 'Anual' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/80">Total:</span>
                                <span class="text-3xl font-black">
                                    €{{ number_format($tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual, 2) }}
                                </span>
                            </div>
                            @if($tipo === 'anual')
                                <p class="text-white/70 text-xs mt-2">
                                    Ahorras €{{ number_format($plan->ahorro_anual, 2) }} ({{ $plan->descuento_anual }}%)
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Características Incluidas -->
                    <div class="space-y-2 text-sm">
                        <p class="font-bold text-brown mb-3">✓ Incluye:</p>
                        <div class="space-y-2 text-gray-600">
                            <p>• {{ $plan->likes_diarios === -1 ? 'Likes ilimitados' : $plan->likes_diarios . ' likes/día' }}</p>
                            @if($plan->super_likes_mes > 0)
                                <p>• {{ $plan->super_likes_mes }} Super Likes/mes</p>
                            @endif
                            @if($plan->ver_quien_te_gusta)
                                <p>• Ver quién te gusta</p>
                            @endif
                            @if($plan->rewind)
                                <p>• Deshacer swipes</p>
                            @endif
                            @if($plan->boost_mensual)
                                <p>• 1 Boost mensual</p>
                            @endif
                            @if($plan->sin_anuncios)
                                <p>• Sin anuncios</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Pago -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-brown mb-6">Método de Pago</h3>

                    <!-- Selector de Método de Pago -->
                    <div class="mb-8" x-data="{ metodo: 'stripe' }">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <button
                                @click="metodo = 'stripe'"
                                :class="metodo === 'stripe' ? 'border-heart-red bg-heart-red/5' : 'border-gray-300'"
                                class="border-2 rounded-2xl p-4 transition hover:border-heart-red"
                            >
                                <img src="https://stripe.com/img/v3/home/social.png" alt="Stripe" class="h-8 mx-auto mb-2">
                                <p class="text-sm font-semibold text-brown">Tarjeta</p>
                            </button>
                            <button
                                @click="metodo = 'paypal'"
                                :class="metodo === 'paypal' ? 'border-heart-red bg-heart-red/5' : 'border-gray-300'"
                                class="border-2 rounded-2xl p-4 transition hover:border-heart-red"
                            >
                                <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_74x46.jpg" alt="PayPal" class="h-8 mx-auto mb-2">
                                <p class="text-sm font-semibold text-brown">PayPal</p>
                            </button>
                        </div>

                        <!-- Formulario Stripe -->
                        <div x-show="metodo === 'stripe'" x-cloak>
                            <form id="payment-form-stripe">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <input type="hidden" name="tipo" value="{{ $tipo }}">

                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-brown mb-2">Nombre en la Tarjeta</label>
                                    <input
                                        type="text"
                                        name="card_holder"
                                        required
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-heart-red focus:ring-0 transition"
                                        placeholder="Juan Pérez"
                                    >
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-brown mb-2">Información de la Tarjeta</label>
                                    <div id="card-element" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl"></div>
                                    <div id="card-errors" class="text-red-500 text-sm mt-2"></div>
                                </div>

                                <button
                                    type="submit"
                                    id="stripe-submit-btn"
                                    class="w-full bg-gradient-to-r from-heart-red to-heart-red-light text-white py-4 px-6 rounded-2xl font-bold hover:shadow-glow transition"
                                >
                                    Pagar €{{ number_format($tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual, 2) }}
                                </button>
                            </form>
                        </div>

                        <!-- PayPal -->
                        <div x-show="metodo === 'paypal'" x-cloak>
                            <div id="paypal-button-container" class="mb-4"></div>

                            <form id="payment-form-paypal" method="POST" action="{{ route('subscriptions.paypal') }}" class="hidden">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <input type="hidden" name="tipo" value="{{ $tipo }}">
                                <input type="hidden" name="order_id" id="paypal-order-id">
                            </form>
                        </div>
                    </div>

                    <!-- Información de Seguridad -->
                    <div class="bg-gray-50 rounded-2xl p-6 mt-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="font-bold text-brown mb-1">Pago 100% Seguro</h4>
                                <p class="text-gray-600 text-sm">
                                    Tu pago está protegido con encriptación SSL. No almacenamos tu información de pago.
                                    Puedes cancelar tu suscripción en cualquier momento.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-500 text-xs text-center mt-6">
                        Al completar la compra, aceptas nuestros
                        <a href="#" class="text-heart-red hover:underline">Términos de Servicio</a> y
                        <a href="#" class="text-heart-red hover:underline">Política de Privacidad</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID_PAYPAL&vault=true&intent=subscription"></script>

<script>
    // Stripe Setup
    const stripe = Stripe('{{ env("STRIPE_PUBLIC_KEY", "pk_test_51...") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    cardElement.mount('#card-element');

    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    const form = document.getElementById('payment-form-stripe');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const submitBtn = document.getElementById('stripe-submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Procesando...';

        const {token, error} = await stripe.createToken(cardElement);

        if (error) {
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'Pagar €{{ number_format($tipo === "mensual" ? $plan->precio_mensual : $plan->precio_anual, 2) }}';
        } else {
            // Enviar token al servidor
            const formData = new FormData(form);
            formData.append('payment_method_id', token.id);

            fetch('{{ route("subscriptions.stripe") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("subscriptions.dashboard") }}';
                } else {
                    alert(data.message || 'Error al procesar el pago');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Pagar €{{ number_format($tipo === "mensual" ? $plan->precio_mensual : $plan->precio_anual, 2) }}';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el pago');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Pagar €{{ number_format($tipo === "mensual" ? $plan->precio_mensual : $plan->precio_anual, 2) }}';
            });
        }
    });

    // PayPal Setup
    paypal.Buttons({
        createSubscription: function(data, actions) {
            // Aquí irá la lógica para crear la suscripción en PayPal
            return actions.subscription.create({
                'plan_id': '{{ $tipo === "mensual" ? $plan->paypal_plan_id_monthly : $plan->paypal_plan_id_yearly }}'
            });
        },
        onApprove: function(data, actions) {
            // El usuario aprobó el pago
            document.getElementById('paypal-order-id').value = data.subscriptionID;
            document.getElementById('payment-form-paypal').submit();
        },
        onError: function(err) {
            console.error('Error PayPal:', err);
            alert('Error al procesar el pago con PayPal');
        }
    }).render('#paypal-button-container');
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
