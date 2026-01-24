@php
    $user = auth()->user();
    $profile = $user->profile ?? null;
    $isVerified = $profile && $profile->verified;
    $hasPendingRequest = \App\Models\VerificationRequest::where('user_id', $user->id)
        ->where('estado', 'pendiente')
        ->exists();
@endphp

@if($profile && !$isVerified && !$hasPendingRequest)
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-4 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-blue-900 text-sm mb-1">Verifica tu perfil</h3>
                <p class="text-blue-700 text-xs mb-3">
                    Los perfiles verificados tienen mayor visibilidad y generan más confianza. Recibe más likes y respuestas verificando tu identidad.
                </p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('verification.create') }}" class="inline-flex items-center gap-1 bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 py-2 rounded-full text-xs font-bold hover:shadow-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verificar ahora
                    </a>
                    <button onclick="this.closest('.bg-gradient-to-r').remove()" class="text-blue-600 text-xs hover:underline">
                        Más tarde
                    </button>
                </div>
            </div>
        </div>
    </div>
@elseif($profile && !$isVerified && $hasPendingRequest)
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-2xl p-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                <svg class="w-5 h-5 text-yellow-800" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-yellow-900 text-sm">Verificación en proceso</h3>
                <p class="text-yellow-700 text-xs">Estamos revisando tu solicitud. Te notificaremos pronto.</p>
            </div>
        </div>
    </div>
@endif
