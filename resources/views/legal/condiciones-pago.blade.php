@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-brown mb-4">Condiciones de Pago y Cancelación</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
            <div class="prose prose-brown max-w-none">
                <section class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        En <strong class="text-brown">Citas Mallorca</strong> queremos que tengas total claridad sobre cómo funcionan los pagos y cancelaciones de nuestros planes de suscripción.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Métodos de Pago Aceptados</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Actualmente aceptamos los siguientes métodos de pago:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li><strong>PayPal:</strong> Puedes pagar directamente con tu cuenta de PayPal</li>
                        <li><strong>Tarjeta de crédito/débito:</strong> Visa, Mastercard, American Express procesadas vía PayPal</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        Todos los pagos son procesados de forma segura a través de PayPal, garantizando la protección de tus datos bancarios.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Procesamiento del Pago</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Al confirmar tu suscripción:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 mt-3">
                        <li>El pago se procesa de inmediato</li>
                        <li>Recibirás un email de confirmación con los detalles de tu suscripción</li>
                        <li>Tendrás acceso inmediato a todas las funcionalidades premium</li>
                        <li>La suscripción se renovará automáticamente según el plan elegido (mensual o anual)</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Renovación Automática</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Tu suscripción se renovará automáticamente al finalizar cada período:
                    </p>
                    <div class="bg-brown/5 rounded-xl p-4 my-4">
                        <ul class="space-y-2 text-gray-700">
                            <li><strong>Plan Mensual:</strong> Se renovará cada mes al mismo precio</li>
                            <li><strong>Plan Anual:</strong> Se renovará cada año al mismo precio</li>
                        </ul>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Recibirás un email de recordatorio antes de cada renovación. El cargo se realizará automáticamente al método de pago que hayas registrado.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Cómo Cancelar tu Suscripción</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Puedes cancelar tu suscripción en cualquier momento siguiendo estos pasos:
                    </p>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-4">
                        <li>Inicia sesión en tu cuenta</li>
                        <li>Ve a <strong>"Mi Suscripción"</strong> en tu perfil</li>
                        <li>Haz clic en <strong>"Cancelar Suscripción"</strong></li>
                        <li>Confirma la cancelación</li>
                    </ol>
                    <p class="text-gray-700 leading-relaxed mt-4">
                        También puedes cancelar directamente desde tu cuenta de PayPal accediendo a la sección de "Pagos Automáticos" o "Suscripciones".
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">¿Qué Sucede al Cancelar?</h2>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 my-4">
                        <p class="text-gray-700 leading-relaxed">
                            <strong>Importante:</strong> Al cancelar tu suscripción, seguirás teniendo acceso completo a las funcionalidades premium hasta que finalice el período por el que ya has pagado.
                        </p>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Por ejemplo:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Si contrataste un plan mensual el 1 de enero y cancelas el 15 de enero, seguirás teniendo acceso premium hasta el 31 de enero.</li>
                        <li>No se realizarán más cargos una vez cancelada la suscripción.</li>
                        <li>Cuando expire el período pagado, tu cuenta volverá automáticamente al Plan Gratuito.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Política de Reembolsos</h2>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-4">
                        <p class="text-gray-700 leading-relaxed font-semibold mb-2">
                            NO REALIZAMOS DEVOLUCIONES
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            Dado que otorgamos acceso inmediato al servicio completo tras el pago, no es posible solicitar reembolso una vez procesado el pago y activada la suscripción. Al contratar, renuncias expresamente al derecho de desistimiento según la normativa europea (Directiva 2011/83/UE).
                        </p>
                    </div>
                    <p class="text-gray-700 leading-relaxed mt-4">
                        Sin embargo, si experimentas algún problema técnico grave que te impida usar el servicio o detectas un cargo incorrecto, contáctanos en
                        <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>
                        y analizaremos tu caso de manera individual.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Cambios de Precio</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Nos reservamos el derecho de modificar los precios de nuestros planes. Sin embargo:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 mt-3">
                        <li>Los cambios de precio NO afectarán a suscripciones ya contratadas hasta la próxima renovación.</li>
                        <li>Te notificaremos con antelación cualquier cambio de precio antes de tu próxima renovación.</li>
                        <li>Siempre tendrás la opción de cancelar antes de que se aplique el nuevo precio.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Fallos en el Pago</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Si tu renovación automática falla por fondos insuficientes, tarjeta vencida u otro motivo:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 mt-3">
                        <li>Intentaremos procesar el pago nuevamente en los días siguientes</li>
                        <li>Te enviaremos un email notificándote del problema</li>
                        <li>Si el pago sigue fallando, tu cuenta volverá al Plan Gratuito</li>
                        <li>Podrás reactivar tu suscripción en cualquier momento actualizando tu método de pago</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Impuestos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Todos los precios mostrados en nuestra web incluyen IVA (21% en España). No hay cargos adicionales ocultos. El precio que ves es el precio que pagas.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Facturas</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Recibirás un recibo de pago por email cada vez que se procese un cargo. Si necesitas una factura formal con tus datos fiscales, contáctanos en
                        <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Seguridad de los Pagos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Todos los pagos se procesan a través de PayPal, que cuenta con certificación PCI-DSS y encriptación SSL. Nosotros NO almacenamos datos de tu tarjeta de crédito en nuestros servidores.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Contacto</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Si tienes dudas sobre pagos, facturación o cancelación, escríbenos a
                        <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>
                        y te responderemos lo antes posible.
                    </p>
                </section>
            </div>
        </div>

        <!-- Botón Volver -->
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" class="text-brown hover:text-heart-red font-semibold transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>
    </div>
</div>
@endsection
