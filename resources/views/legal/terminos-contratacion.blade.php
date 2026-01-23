@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-brown mb-4">Condiciones de Contratación</h1>
            <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
            <div class="prose prose-brown max-w-none">
                <section class="mb-8">
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Al contratar un plan de suscripción en <strong class="text-brown">Citas Mallorca</strong>, aceptas las siguientes condiciones de contratación.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Objeto del Contrato</h2>
                    <p class="text-gray-700 leading-relaxed">
                        La contratación de un plan de suscripción te otorga acceso completo a las funcionalidades premium de la plataforma, tales como:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 mt-3">
                        <li>Likes ilimitados</li>
                        <li>Ver quién te ha dado like</li>
                        <li>Mensajería sin restricciones</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Duración y Renovación</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Las suscripciones se renuevan automáticamente según el período contratado:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li><strong>Plan Mensual:</strong> Se renueva cada mes hasta su cancelación.</li>
                        <li><strong>Plan Anual:</strong> Se renueva cada año hasta su cancelación.</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        La renovación se carga automáticamente a tu método de pago registrado (PayPal, tarjeta de crédito/débito). Recibirás un recordatorio por correo electrónico antes de cada renovación.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Precio e Impuestos</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Los precios se muestran con IVA incluido. Los planes vigentes son:
                    </p>
                    <div class="bg-brown/5 rounded-xl p-4 my-4">
                        <ul class="space-y-2 text-gray-700">
                            <li><strong>Plan Gratis:</strong> 0,00 € (acceso limitado)</li>
                            <li><strong>Plan Mensual:</strong> 4,99 €/mes (IVA incluido)</li>
                            <li><strong>Plan Anual:</strong> 29,99 €/año (IVA incluido)</li>
                        </ul>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Los precios pueden cambiar, pero cualquier modificación será comunicada con antelación y NO afectará a suscripciones ya contratadas hasta la próxima renovación.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Forma de Pago</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Actualmente aceptamos pagos mediante:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 mt-3">
                        <li>PayPal</li>
                        <li>Tarjeta de crédito/débito (Visa, Mastercard, American Express) vía PayPal</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        Al contratar, autorizas a PayPal o al proveedor del pago a realizar cargos automáticos según el período de tu suscripción.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Acceso Inmediato al Servicio</h2>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-4">
                        <p class="text-gray-700 leading-relaxed font-semibold">
                            IMPORTANTE: Al contratar tu suscripción, tendrás acceso inmediato a todas las funcionalidades premium. Por tanto, según la normativa europea de consumo (Directiva 2011/83/UE), renuncias expresamente a tu derecho de desistimiento de 14 días, dado que el servicio comienza a ejecutarse de inmediato.
                        </p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Cancelación de la Suscripción</h2>
                    <p class="text-gray-700 leading-relaxed mb-3">
                        Puedes cancelar tu suscripción en cualquier momento desde tu perfil de usuario, en la sección <strong>"Mi Suscripción"</strong>.
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                        <li>Si cancelas, tu suscripción seguirá activa hasta el final del período ya pagado.</li>
                        <li>No se realizarán más cargos tras la cancelación.</li>
                        <li>Podrás seguir usando las funcionalidades premium hasta que expire el período actual.</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        Una vez finalizado el período, tu cuenta volverá al Plan Gratuito con acceso limitado.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Política de Devoluciones</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Dado que otorgamos acceso inmediato al servicio tras el pago, <strong class="text-heart-red">no se realizan devoluciones</strong> una vez procesado el pago y activada la suscripción.
                    </p>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        Sin embargo, si experimentas problemas técnicos o cargos incorrectos, contáctanos en
                        <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>
                        y estudiaremos tu caso.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Suspensión del Servicio</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Nos reservamos el derecho de suspender o cancelar tu suscripción si detectamos:
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4 mt-3">
                        <li>Uso fraudulento o no autorizado</li>
                        <li>Incumplimiento de los Términos y Condiciones de Uso</li>
                        <li>Comportamiento abusivo hacia otros usuarios</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-3">
                        En tales casos, no se realizará devolución del importe pagado.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Modificación de las Condiciones</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Podemos modificar estas condiciones de contratación en cualquier momento. Te notificaremos de cambios importantes con antelación. Las nuevas condiciones aplicarán a partir de la próxima renovación de tu suscripción.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Jurisdicción Aplicable</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Estas condiciones se rigen por la legislación española. Cualquier disputa será resuelta en los tribunales de Palma de Mallorca, Islas Baleares, España.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-brown mb-4">Contacto</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Para consultas sobre facturación, pagos o condiciones de contratación, escríbenos a
                        <a href="mailto:info@citasmallorca.es" class="text-heart-red hover:underline font-semibold">info@citasmallorca.es</a>.
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
