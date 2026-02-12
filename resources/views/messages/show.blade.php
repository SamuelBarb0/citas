@extends('layouts.app')

@section('content')
<div class="fixed inset-0 bg-gradient-to-br from-cream via-white to-cream flex flex-col" style="padding-bottom: 4rem;">
    <!-- Header fijo con info del match -->
    <div class="flex-shrink-0 bg-white/90 backdrop-blur-lg border-b border-gray-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 flex-1">
                    <!-- Botón volver -->
                    <a href="{{ route('messages') }}" class="text-brown hover:text-heart-red transition group">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition border-2 border-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Info del usuario -->
                    <a href="{{ route('profile.public', $otherUser->id) }}" class="flex items-center gap-3 flex-1 hover:opacity-80 transition group">
                        <div class="relative">
                            <img
                                src="{{ $otherUser->profile->foto_principal ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&size=200&background=A67C52&color=fff' }}"
                                alt="{{ $otherUser->name }}"
                                class="w-14 h-14 rounded-full object-cover border-4 border-white shadow-lg"
                            >
                            @if($otherUser->isOnline())
                                <!-- Badge online - Solo si estuvo activo en los últimos 5 minutos -->
                                <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full" title="En línea"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2 class="font-black text-brown text-lg truncate">
                                {{ $otherUser->profile->nombre ?? $otherUser->name }}
                            </h2>
                            <p class="text-sm text-gray-500 flex items-center gap-1 truncate">
                                @if($otherUser->isOnline())
                                    <span class="text-green-500 font-semibold">En línea</span>
                                @else
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $otherUser->profile->ciudad ?? '' }}
                                @endif
                            </p>
                        </div>
                    </a>

                    <!-- Botón ver perfil -->
                    <a href="{{ route('profile.public', $otherUser->id) }}" class="hidden sm:block bg-gradient-to-r from-heart-red to-heart-red-light text-white px-5 py-2 rounded-full font-semibold hover:shadow-glow transition text-sm">
                        Ver Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Área de mensajes con diseño WhatsApp/Telegram style -->
    <div class="flex-1 overflow-hidden flex flex-col">
        <div class="max-w-4xl mx-auto w-full h-full flex flex-col">
            <!-- Container de mensajes -->
            <div class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6" id="messages-container">
                @if($messages->count() > 0)
                    <div class="space-y-4">
                        @php
                            $lastDate = null;
                        @endphp
                        @foreach($messages as $message)
                            @php
                                $messageDate = $message->created_at->format('Y-m-d');
                            @endphp

                            <!-- Separador de fecha -->
                            @if($lastDate !== $messageDate)
                                <div class="flex justify-center my-6">
                                    <div class="bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-md text-xs font-bold text-gray-500">
                                        {{ $message->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                @php $lastDate = $messageDate; @endphp
                            @endif

                            @if($message->sender_id == auth()->id())
                                <!-- Mensaje enviado (derecha) -->
                                <div class="flex justify-end animate-slide-in-right">
                                    <div class="max-w-md">
                                        <div class="bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-4 rounded-3xl rounded-tr-md shadow-lg">
                                            <p class="text-sm leading-relaxed break-words">{{ $message->mensaje }}</p>
                                        </div>
                                        <div class="flex items-center justify-end gap-1 mt-1 px-2">
                                            <p class="text-xs text-gray-500">
                                                {{ $message->created_at->format('H:i') }}
                                            </p>
                                            <!-- Checkmarks de lectura -->
                                            @if($message->leido)
                                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                                <svg class="w-4 h-4 text-blue-500 -ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Mensaje recibido (izquierda) -->
                                <div class="flex justify-start animate-slide-in-left">
                                    <div class="max-w-md">
                                        <div class="bg-white text-brown px-6 py-4 rounded-3xl rounded-tl-md shadow-lg border-2 border-gray-100">
                                            <p class="text-sm leading-relaxed break-words">{{ $message->mensaje }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 px-2">
                                            {{ $message->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <!-- Sin mensajes aún - Estado inicial -->
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center max-w-md">
                            <!-- Ilustración match -->
                            <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-heart-red to-heart-red-light rounded-full flex items-center justify-center shadow-2xl">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-black text-brown mb-3">¡Es un Match!</h3>
                            <p class="text-gray-600 mb-2 text-lg">
                                Tú y <span class="font-bold text-heart-red">{{ $otherUser->profile->nombre ?? $otherUser->name }}</span> os gustáis
                            </p>
                            <p class="text-gray-500 text-sm mb-8">
                                Envía el primer mensaje para romper el hielo 🔥
                            </p>

                            <!-- Sugerencias de mensajes -->
                            <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100">
                                <p class="text-xs font-bold text-gray-500 mb-3 uppercase">Sugerencias:</p>
                                <div class="space-y-2">
                                    <button onclick="document.getElementById('mensaje-input').value = '¡Hola! 👋 ¿Cómo estás?'; document.getElementById('mensaje-input').focus();" class="w-full bg-cream hover:bg-brown hover:text-white transition text-brown px-4 py-2 rounded-xl text-sm font-semibold text-left">
                                        "¡Hola! 👋 ¿Cómo estás?"
                                    </button>
                                    <button onclick="document.getElementById('mensaje-input').value = 'Me encantó tu perfil 😊'; document.getElementById('mensaje-input').focus();" class="w-full bg-cream hover:bg-brown hover:text-white transition text-brown px-4 py-2 rounded-xl text-sm font-semibold text-left">
                                        "Me encantó tu perfil 😊"
                                    </button>
                                    <button onclick="document.getElementById('mensaje-input').value = '¿Qué tal tu día?'; document.getElementById('mensaje-input').focus();" class="w-full bg-cream hover:bg-brown hover:text-white transition text-brown px-4 py-2 rounded-xl text-sm font-semibold text-left">
                                        "¿Qué tal tu día?"
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Formulario de envío fijo - compacto y pegado a los mensajes -->
            <div class="flex-shrink-0 bg-white border-t border-gray-200 message-form-container">
                <div class="px-4 sm:px-6 lg:px-8 py-3">
                    @php
                        $currentUser = auth()->user();
                        $currentSubscription = $currentUser->activeSubscription;
                        $currentPlan = $currentSubscription ? $currentSubscription->plan : null;

                        $receiverSubscription = $otherUser->activeSubscription;
                        $receiverPlan = $receiverSubscription ? $receiverSubscription->plan : null;

                        // Determinar si puede enviar mensajes
                        $canSendMessage = true;
                        $restrictionMessage = null;
                        $remainingMessages = null;
                        $remainingResponses = null;

                        if (!$currentSubscription || ($currentPlan && $currentPlan->slug === 'free')) {
                            // Usuario Gratis: solo puede responder (regla 1:1 alternado)
                            $lastMessage = $match->messages()->latest()->first();

                            if (!$lastMessage) {
                                // No hay mensajes, no puede iniciar
                                $canSendMessage = false;
                                $restrictionMessage = 'Los usuarios gratuitos solo pueden responder mensajes. Actualiza tu plan para iniciar conversaciones.';
                                $remainingResponses = 0;
                            } elseif ($lastMessage->sender_id == auth()->id()) {
                                // El último mensaje lo envió el usuario, debe esperar respuesta
                                $canSendMessage = false;
                                $restrictionMessage = "Has respondido el último mensaje. Espera a que {$otherUser->profile->nombre} te responda.";
                                $remainingResponses = 0;
                            } else {
                                // El último mensaje lo envió el otro usuario, puede responder
                                $canSendMessage = true;
                                $remainingResponses = 1;
                            }
                        } else {
                            // Verificar límites según el plan
                            if (!$currentSubscription->canSendMessageTo($otherUser, $match->id)) {
                                $canSendMessage = false;
                                $remainingMessages = $currentSubscription->getRemainingWeeklyMessages();

                                if ($remainingMessages === 0 && $currentPlan->slug === 'basico') {
                                    $restrictionMessage = 'Has alcanzado tu límite de 3 mensajes semanales a usuarios gratuitos. Actualiza a Premium para mensajes ilimitados.';
                                } else {
                                    $restrictionMessage = 'No puedes enviar más mensajes. Actualiza tu plan para continuar.';
                                }
                            } else {
                                // Mostrar mensajes restantes para usuarios Básico enviando a Gratis
                                if ($currentPlan->slug === 'basico' && (!$receiverPlan || $receiverPlan->slug === 'free')) {
                                    $remainingMessages = $currentSubscription->getRemainingWeeklyMessages();
                                }
                            }
                        }
                    @endphp

                    {{-- Indicador minimalista de mensajes restantes (solo si aplica) --}}
                    @if($remainingResponses !== null && $remainingResponses > 0 && (!$currentPlan || $currentPlan->slug === 'free'))
                        <div class="mb-2 text-center">
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                </svg>
                                {{ $remainingResponses }} {{ $remainingResponses === 1 ? 'respuesta disponible' : 'respuestas disponibles' }}
                            </span>
                        </div>
                    @elseif($remainingMessages !== null && $remainingMessages >= 0 && $remainingMessages <= 3 && $currentPlan && $currentPlan->slug === 'basico')
                        <div class="mb-2 text-center">
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                </svg>
                                {{ $remainingMessages }} mensajes semanales restantes
                            </span>
                        </div>
                    @endif

                    @if(!$canSendMessage)
                        <!-- Mensaje de restricción compacto -->
                        <div class="flex items-center gap-3 bg-gray-50 rounded-2xl p-3 border border-gray-200">
                            <div class="flex-1">
                                <p class="text-gray-600 text-sm">{{ $restrictionMessage }}</p>
                            </div>
                            <a href="{{ route('subscriptions.index') }}" class="flex-shrink-0 bg-gradient-to-r from-heart-red to-heart-red-light text-white px-4 py-2 rounded-full font-bold text-sm hover:shadow-glow transition">
                                Ver Planes
                            </a>
                        </div>
                    @else
                        <!-- Formulario normal -->
                        <form id="message-form" action="{{ route('messages.store', $match->id) }}" method="POST" class="flex gap-3 items-end">
                            @csrf
                            <div class="flex-1">
                                <textarea
                                    id="mensaje-input"
                                    name="mensaje"
                                    placeholder="Escribe un mensaje..."
                                    required
                                    maxlength="1000"
                                    rows="1"
                                    class="w-full px-6 py-4 border-2 border-gray-300 rounded-3xl focus:border-heart-red focus:ring-0 transition resize-none text-sm"
                                    style="max-height: 120px;"
                                ></textarea>
                                @error('mensaje')
                                    <p class="text-red-500 text-xs mt-1 px-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <button
                                id="send-button"
                                type="submit"
                                class="bg-gradient-to-r from-heart-red to-heart-red-light text-white p-4 rounded-full font-bold hover:shadow-glow transition shadow-lg flex items-center justify-center group"
                            >
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Notificación de éxito temporal (desaparece automáticamente) --}}
@if(session('success'))
    <div id="success-notification" class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white px-4 py-2 rounded-full shadow-lg text-sm font-semibold opacity-0 transition-opacity duration-300">
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Enviado
        </span>
    </div>
    <script>
        // Mostrar y ocultar la notificación automáticamente
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('success-notification');
            if (notification) {
                setTimeout(() => notification.style.opacity = '1', 100);
                setTimeout(() => notification.style.opacity = '0', 2000);
                setTimeout(() => notification.remove(), 2500);
            }
        });
    </script>
@endif

<style>
    @keyframes slide-in-right {
        from {
            transform: translateX(50px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slide-in-left {
        from {
            transform: translateX(-50px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }
    .animate-slide-in-left {
        animation: slide-in-left 0.3s ease-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        const textarea = document.getElementById('mensaje-input');
        const form = document.getElementById('message-form');
        const sendButton = document.getElementById('send-button');
        const matchId = {{ $match->id }};
        const currentUserId = {{ auth()->id() }};
        let lastMessageId = {{ $messages->last()->id ?? 0 }};
        let isPolling = true;
        let pollingInterval;
        let isSubmitting = false; // Prevenir doble envío

        // Auto scroll al final del chat al cargar
        function scrollToBottom(smooth = false) {
            if (smooth) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            } else {
                container.scrollTop = container.scrollHeight;
            }
        }
        scrollToBottom();

        // Auto-resize del textarea
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Manejar Enter para enviar (Shift+Enter para nueva línea)
        textarea.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                if (!isSubmitting && textarea.value.trim()) {
                    form.requestSubmit();
                }
            }
        });

        // Enviar formulario mediante AJAX
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            if (isSubmitting) {
                return false;
            }

            if (!textarea.value.trim()) {
                return false;
            }

            isSubmitting = true;
            sendButton.disabled = true;
            sendButton.classList.add('opacity-50', 'cursor-not-allowed');

            const messageText = textarea.value.trim();
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    // Crear y mostrar el mensaje inmediatamente
                    const newMessage = {
                        id: data.message_id,
                        mensaje: messageText,
                        created_at: new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }),
                        is_mine: true
                    };

                    const messageElement = createMessageElement(newMessage);
                    container.appendChild(messageElement);
                    lastMessageId = data.message_id;

                    // Limpiar textarea y resetear altura
                    textarea.value = '';
                    textarea.style.height = 'auto';

                    // Scroll al final
                    setTimeout(() => scrollToBottom(true), 100);

                    // Si el usuario es gratis, deshabilitar el formulario hasta que reciba respuesta
                    if (data.can_send === false) {
                        updateFormPermissions(false, data.restriction_message);
                    }
                } else {
                    // Mostrar error
                    const errorData = await response.json();
                    alert(errorData.error || 'Error al enviar el mensaje');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Error al enviar el mensaje. Por favor, intenta de nuevo.');
            } finally {
                isSubmitting = false;
                sendButton.disabled = false;
                sendButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });

        // Focus automático en el input
        textarea.focus();

        // Función para crear elemento de mensaje
        function createMessageElement(message) {
            const div = document.createElement('div');
            div.className = message.is_mine
                ? 'flex justify-end mb-4 animate-slide-in-right'
                : 'flex justify-start mb-4 animate-slide-in-left';

            if (message.is_mine) {
                div.innerHTML = `
                    <div class="max-w-[75%]">
                        <div class="bg-gradient-to-r from-heart-red to-heart-red-light text-white px-6 py-3 rounded-3xl rounded-tr-sm shadow-lg">
                            <p class="text-sm break-words">${escapeHtml(message.mensaje)}</p>
                        </div>
                        <p class="text-xs text-gray-400 mt-1 text-right">${message.created_at}</p>
                    </div>
                `;
            } else {
                const senderPhoto = message.sender_photo || '';
                const senderName = message.sender_name || 'Usuario';
                div.innerHTML = `
                    <div class="flex gap-3 max-w-[75%]">
                        ${senderPhoto
                            ? `<img src="${senderPhoto}" alt="${senderName}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">`
                            : `<div class="w-10 h-10 rounded-full bg-gradient-to-br from-brown to-heart-red flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>`
                        }
                        <div>
                            <div class="bg-gray-100 px-6 py-3 rounded-3xl rounded-tl-sm shadow-md">
                                <p class="text-sm text-gray-800 break-words">${escapeHtml(message.mensaje)}</p>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">${message.created_at}</p>
                        </div>
                    </div>
                `;
            }
            return div;
        }

        // Escapar HTML para prevenir XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Actualizar permisos del formulario dinámicamente
        function updateFormPermissions(canSend, restrictionMessage) {
            const formContainer = document.querySelector('.message-form-container');
            if (!formContainer) return;

            if (canSend) {
                // Habilitar formulario - recargar para mostrar el formulario correcto
                window.location.reload();
            } else {
                // Deshabilitar formulario y mostrar restricción
                const formElement = document.getElementById('message-form');
                if (formElement && restrictionMessage) {
                    formElement.style.display = 'none';

                    // Crear mensaje de restricción
                    const restrictionDiv = document.createElement('div');
                    restrictionDiv.id = 'restriction-message';
                    restrictionDiv.className = 'flex items-center gap-3 bg-gray-50 rounded-2xl p-3 border border-gray-200';
                    restrictionDiv.innerHTML = `
                        <div class="flex-1">
                            <p class="text-gray-600 text-sm">${restrictionMessage}</p>
                        </div>
                        <a href="/subscriptions" class="flex-shrink-0 bg-gradient-to-r from-heart-red to-heart-red-light text-white px-4 py-2 rounded-full font-bold text-sm hover:shadow-glow transition">
                            Ver Planes
                        </a>
                    `;

                    formElement.parentNode.appendChild(restrictionDiv);
                }
            }
        }

        // Polling para mensajes nuevos (cada 3 segundos)
        async function checkNewMessages() {
            if (!isPolling) return;

            try {
                const response = await fetch(`/messages/${matchId}/new?last_message_id=${lastMessageId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) return;

                const data = await response.json();

                if (data.count > 0) {
                    // Verificar si el usuario está al final del chat
                    const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;

                    // Agregar mensajes nuevos
                    data.messages.forEach(message => {
                        const messageElement = createMessageElement(message);
                        container.appendChild(messageElement);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });

                    // Si hay mensajes del otro usuario, actualizar permisos del formulario
                    const hasMessagesFromOther = data.messages.some(m => !m.is_mine);
                    if (hasMessagesFromOther && data.can_send !== undefined) {
                        updateFormPermissions(data.can_send, data.restriction_message);
                    }

                    // Scroll automático si estaba al final o si el mensaje es propio
                    if (isAtBottom || data.messages.some(m => m.is_mine)) {
                        setTimeout(() => scrollToBottom(true), 100);
                    }
                }
            } catch (error) {
                console.error('Error checking new messages:', error);
            }
        }

        // Iniciar polling cada 3 segundos
        pollingInterval = setInterval(checkNewMessages, 3000);

        // Detener polling cuando el usuario sale de la página
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                isPolling = false;
            } else {
                isPolling = true;
                checkNewMessages(); // Check inmediatamente al volver
            }
        });

        // Limpiar intervalo al salir
        window.addEventListener('beforeunload', function() {
            clearInterval(pollingInterval);
        });
    });
</script>
@endsection
