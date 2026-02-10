<section id="delete-account" class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Eliminar Cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Una vez que tu cuenta sea eliminada, todos tus datos seran borrados permanentemente, incluyendo tu perfil, fotos, matches, conversaciones y cualquier informacion personal. Esta accion no se puede deshacer.
        </p>
    </header>

    <!-- Aviso importante -->
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>Atencion:</strong> Si tienes una suscripcion activa, primero debes cancelarla desde
                    <a href="{{ route('subscriptions.dashboard') }}" class="underline font-semibold hover:text-red-800">tu panel de suscripcion</a>.
                </p>
            </div>
        </div>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Eliminar mi Cuenta Permanentemente
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">
                    ¿Seguro que quieres eliminar tu cuenta?
                </h2>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600 mb-3">
                    Al eliminar tu cuenta se borrara permanentemente:
                </p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Tu perfil y todas tus fotos
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Todos tus matches y conexiones
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Todas tus conversaciones
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Tu historial de suscripciones
                    </li>
                </ul>
            </div>

            <div class="mb-6">
                <x-input-label for="password" value="Introduce tu contrasena para confirmar" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full"
                    placeholder="Tu contrasena actual"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="flex-1 justify-center">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="flex-1 justify-center bg-red-600 hover:bg-red-700">
                    Si, Eliminar mi Cuenta
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
