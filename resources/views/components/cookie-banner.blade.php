<!-- Overlay oscuro para el banner de cookies -->
<div id="cookie-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9998]" style="display: none;"></div>

<!-- Banner de Cookies - Modal centrado y prominente -->
<div id="cookie-banner" class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4" style="display: none;">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl max-w-lg w-full" id="cookie-banner-inner">
        <!-- Header con icono grande -->
        <div class="bg-gradient-to-r from-brown to-heart-red text-white p-4 sm:p-6 rounded-t-2xl sm:rounded-t-3xl text-center">
            <span class="text-4xl sm:text-6xl block mb-2 sm:mb-3">🍪</span>
            <h2 id="cookie-banner-title" class="text-xl sm:text-2xl font-black">Tu privacidad es importante</h2>
        </div>

        <!-- Contenido -->
        <div class="p-4 sm:p-6">
            <p id="cookie-banner-text" class="text-gray-700 text-sm sm:text-base leading-relaxed text-center mb-4 sm:mb-6">
                Utilizamos cookies para mejorar tu experiencia en <strong class="text-brown">Citas Mallorca</strong>.
                Puedes aceptar todas, rechazarlas o personalizar tus preferencias.
            </p>

            <a href="{{ route('legal.cookies') }}" class="block text-center text-brown font-semibold hover:underline mb-4 sm:mb-6 text-sm sm:text-base">
                Leer política de cookies
            </a>

            <!-- Botones de acción grandes y claros -->
            <div class="space-y-2 sm:space-y-3">
                <button onclick="acceptAllCookies()" class="w-full py-3 sm:py-4 bg-gradient-to-r from-brown to-heart-red text-white rounded-full font-bold text-base sm:text-lg hover:shadow-lg hover:scale-[1.02] transition-all">
                    Aceptar todas
                </button>

                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                    <button onclick="rejectCookies()" class="py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition text-xs sm:text-sm">
                        Solo necesarias
                    </button>
                    <button onclick="configureCookies()" class="py-2.5 sm:py-3 bg-white border-2 border-brown text-brown rounded-full font-semibold hover:bg-cream transition text-xs sm:text-sm">
                        Configurar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Configuración de Cookies -->
<div id="cookie-config-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[10000] flex items-center justify-center p-3" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[95vh] overflow-y-auto">
        <!-- Header del modal -->
        <div class="bg-gradient-to-r from-brown to-heart-red text-white p-4 rounded-t-2xl sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold">⚙️ Configuración de Cookies</h2>
                    <p class="text-white text-opacity-90 text-xs mt-1">Personaliza tus preferencias</p>
                </div>
                <button onclick="closeConfigModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Contenido del modal -->
        <div class="p-4 space-y-3">
            <!-- Cookies Necesarias (siempre activas) -->
            <div class="border-2 border-gray-200 rounded-lg p-3 bg-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🔒</span>
                        <h3 class="text-sm font-bold text-brown">Cookies Necesarias</h3>
                    </div>
                    <div class="bg-green-500 text-white px-2 py-0.5 rounded-full text-[10px] font-semibold">
                        Siempre activas
                    </div>
                </div>
                <p class="text-gray-600 text-xs leading-relaxed">
                    Esenciales para el funcionamiento del sitio. Permiten la navegación básica y acceso a áreas seguras.
                </p>
            </div>

            <!-- Cookies Analíticas -->
            <div class="border-2 border-gray-200 rounded-lg p-3 hover:border-brown transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">📊</span>
                        <h3 class="text-sm font-bold text-brown">Cookies Analíticas</h3>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="cookie-analytics" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brown peer-focus:ring-opacity-30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brown"></div>
                    </label>
                </div>
                <p class="text-gray-600 text-xs leading-relaxed">
                    Ayudan a entender cómo interactúas con el sitio. Información anónima sobre páginas visitadas y tiempo de permanencia.
                </p>
            </div>

            <!-- Cookies de Marketing -->
            <div class="border-2 border-gray-200 rounded-lg p-3 hover:border-brown transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🎯</span>
                        <h3 class="text-sm font-bold text-brown">Cookies de Marketing</h3>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="cookie-marketing" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brown peer-focus:ring-opacity-30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brown"></div>
                    </label>
                </div>
                <p class="text-gray-600 text-xs leading-relaxed">
                    Muestran anuncios relevantes basados en tus intereses y miden la efectividad de campañas publicitarias.
                </p>
            </div>

            <!-- Cookies de Preferencias -->
            <div class="border-2 border-gray-200 rounded-lg p-3 hover:border-brown transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">⚡</span>
                        <h3 class="text-sm font-bold text-brown">Cookies de Preferencias</h3>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="cookie-preferences" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brown peer-focus:ring-opacity-30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brown"></div>
                    </label>
                </div>
                <p class="text-gray-600 text-xs leading-relaxed">
                    Recuerdan tus preferencias (idioma, región, configuraciones) para una experiencia personalizada.
                </p>
            </div>
        </div>

        <!-- Footer del modal con botones -->
        <div class="bg-gray-50 p-4 rounded-b-2xl flex gap-2 sticky bottom-0">
            <button onclick="saveCustomCookies()" class="flex-1 bg-gradient-to-r from-brown to-heart-red text-white py-2.5 px-4 rounded-full font-semibold hover:shadow-lg transition text-sm">
                💾 Guardar
            </button>
            <button onclick="closeConfigModal()" class="flex-1 bg-white border-2 border-gray-300 text-gray-700 py-2.5 px-4 rounded-full font-semibold hover:bg-gray-100 transition text-sm">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
// Verificar si ya existe una preferencia de cookies guardada
document.addEventListener('DOMContentLoaded', function() {
    const cookieConsent = getCookie('cookie_consent');

    // Solo mostrar el banner si NO hay cookie guardada
    if (!cookieConsent) {
        setTimeout(function() {
            showCookieBanner();
        }, 500);
    }
});

// Función para obtener cookie
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

// Función para establecer cookie
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${value};${expires};path=/`;
}

// Aceptar todas las cookies
function acceptAllCookies() {
    const preferences = {
        necessary: true,
        analytics: true,
        marketing: true,
        preferences: true
    };

    setCookie('cookie_consent', JSON.stringify(preferences), 365);
    hideCookieBanner();
}

// Rechazar cookies (solo necesarias)
function rejectCookies() {
    const preferences = {
        necessary: true,
        analytics: false,
        marketing: false,
        preferences: false
    };

    setCookie('cookie_consent', JSON.stringify(preferences), 365);
    hideCookieBanner();
}

// Abrir modal de configuración
function configureCookies() {
    document.getElementById('cookie-config-modal').style.display = 'flex';
}

// Cerrar modal de configuración
function closeConfigModal() {
    document.getElementById('cookie-config-modal').style.display = 'none';
}

// Guardar preferencias personalizadas
function saveCustomCookies() {
    const preferences = {
        necessary: true,
        analytics: document.getElementById('cookie-analytics').checked,
        marketing: document.getElementById('cookie-marketing').checked,
        preferences: document.getElementById('cookie-preferences').checked
    };

    setCookie('cookie_consent', JSON.stringify(preferences), 365);
    closeConfigModal();
    hideCookieBanner();
}

// Ocultar el banner de cookies
function hideCookieBanner() {
    const banner = document.getElementById('cookie-banner');
    const overlay = document.getElementById('cookie-overlay');

    if (banner) banner.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
}

// Mostrar el banner de cookies (para el botón del footer)
function showCookieBanner() {
    const banner = document.getElementById('cookie-banner');
    const overlay = document.getElementById('cookie-overlay');

    if (overlay) overlay.style.display = 'block';
    if (banner) banner.style.display = 'flex';
}
</script>
