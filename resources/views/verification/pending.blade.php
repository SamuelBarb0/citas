@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cream via-white to-cream py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full mb-4 shadow-lg animate-pulse">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-4xl font-black text-brown mb-3">Verificación en proceso</h1>
            <p class="text-gray-600 text-lg">Tu solicitud está siendo revisada por nuestro equipo</p>
        </div>

        <!-- Card principal -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 p-8">
            <!-- Estado -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-yellow-900 mb-2">Estamos revisando tu identidad</h3>
                        <p class="text-yellow-800 text-sm mb-3">
                            Enviaste tu foto de verificación el <strong>{{ $pendingRequest->created_at->format('d/m/Y') }}</strong> a las {{ $pendingRequest->created_at->format('H:i') }}.
                        </p>
                        <p class="text-yellow-800 text-sm">
                            Nuestro equipo está verificando que seas una persona real. Este proceso toma entre <strong>24 y 48 horas</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información -->
            <div class="mb-6">
                <h3 class="font-bold text-brown mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    ¿Qué pasa después?
                </h3>
                <ul class="space-y-2 text-gray-700 text-sm ml-7">
                    <li class="flex items-start gap-2">
                        <span class="text-green-600 font-bold">✓</span>
                        <span>Si tu foto cumple los requisitos, aprobaremos tu cuenta y podrás usar Citas Mallorca</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-600 font-bold">✗</span>
                        <span>Si tu foto no cumple los requisitos, te enviaremos un email explicando el motivo y podrás enviar una nueva</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 font-bold">📧</span>
                        <span>Te notificaremos por email cuando tu solicitud sea revisada</span>
                    </li>
                </ul>
            </div>

            <!-- Advertencia -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-red-800">
                        <p class="font-semibold mb-1">No puedes usar la aplicación hasta ser verificado</p>
                        <p>Por seguridad, no podrás acceder a Citas Mallorca hasta que confirmemos tu identidad. Ten paciencia, te notificaremos pronto.</p>
                    </div>
                </div>
            </div>

            <!-- Botón de salir -->
            <div class="mt-6 text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-brown font-medium text-sm underline">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Ayuda -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>¿Tienes problemas? <a href="mailto:soporte@citasmallorca.com" class="text-brown font-medium hover:underline">Contacta con soporte</a></p>
        </div>
    </div>
</div>
@endsection
