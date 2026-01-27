<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">¡Pago Aprobado!</h2>
                        <p class="text-gray-600">Tu pago con PayPal se ha procesado correctamente.</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="font-semibold text-lg mb-4">Detalles de la suscripción:</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Plan:</span>
                                <span class="font-semibold">{{ $plan->nombre }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tipo:</span>
                                <span class="font-semibold">{{ ucfirst($tipo) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monto:</span>
                                <span class="font-semibold">{{ $tipo === 'mensual' ? $plan->precio_mensual : $plan->precio_anual }}€</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Estamos activando tu suscripción. Esto solo tomará un momento...
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="activation-status" class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500"></div>
                        <p class="mt-2 text-gray-600">Activando tu suscripción...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Activar la suscripción automáticamente al cargar la página con reintentos
        document.addEventListener('DOMContentLoaded', function() {
            const subscriptionId = '{{ $subscriptionId }}';
            const planId = '{{ $plan->id }}';
            const tipo = '{{ $tipo }}';
            const statusDiv = document.getElementById('activation-status');
            let retryCount = 0;
            const maxRetries = 3;

            function activateSubscription() {
                fetch('{{ route("subscriptions.paypal.activate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        subscription_id: subscriptionId,
                        plan_id: planId,
                        tipo: tipo
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        statusDiv.innerHTML = `
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-green-600 font-semibold mb-4">${data.message}</p>
                            <a href="${data.redirect_url}" class="inline-flex items-center px-4 py-2 bg-pink-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 focus:bg-pink-700 active:bg-pink-900 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ir a Mi Suscripción
                            </a>
                        `;

                        // Redirigir automáticamente después de 2 segundos
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000);
                    } else {
                        // Si el error indica que ya existe, redirigir
                        if (data.message && data.message.includes('ya existe')) {
                            window.location.href = '{{ route("subscriptions.dashboard") }}';
                            return;
                        }

                        statusDiv.innerHTML = `
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-red-600 font-semibold mb-4">${data.message}</p>
                            <p class="text-gray-500 text-sm mb-4">ID de suscripción: ${subscriptionId}</p>
                            <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                Volver a Planes
                            </a>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    retryCount++;

                    if (retryCount < maxRetries) {
                        statusDiv.innerHTML = `
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-pink-500"></div>
                            <p class="mt-2 text-gray-600">Reintentando activación... (${retryCount}/${maxRetries})</p>
                        `;
                        // Reintentar después de 2 segundos
                        setTimeout(activateSubscription, 2000);
                    } else {
                        statusDiv.innerHTML = `
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <p class="text-red-600 font-semibold mb-2">Error al activar la suscripción.</p>
                            <p class="text-gray-500 text-sm mb-4">Tu pago fue procesado. Por favor, contacta con soporte con este ID: <strong>${subscriptionId}</strong></p>
                            <div class="flex gap-3 justify-center">
                                <button onclick="location.reload()" class="inline-flex items-center px-4 py-2 bg-pink-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 transition ease-in-out duration-150">
                                    Reintentar
                                </button>
                                <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                    Volver
                                </a>
                            </div>
                        `;
                    }
                });
            }

            // Iniciar activación
            activateSubscription();
        });
    </script>
</x-app-layout>
