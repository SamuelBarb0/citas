<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">Gestión de SEO</h1>
                        <p class="text-white/80 mt-1">Configura meta tags para cada página</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-4 py-2 bg-white/20 text-white rounded-lg font-semibold hover:bg-white/30">
                            ← Volver al Panel
                        </a>
                        <a href="{{ route('admin.seo.create') }}"
                           class="px-6 py-3 bg-white text-brown rounded-xl font-bold hover:shadow-lg transition">
                            + Nueva Configuración SEO
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 p-4 rounded">
                        <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 font-semibold">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Tabla de configuraciones SEO -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-900">Páginas Configuradas</h2>
                    </div>

                    @if($seoSettings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Página</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Index/Follow</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open Graph</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($seoSettings as $setting)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 text-sm font-bold text-brown bg-brown-light rounded-full">
                                                    {{ $setting->page_key }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $setting->title ?: 'Sin título' }}</div>
                                                <div class="text-sm text-gray-500 truncate max-w-md">{{ $setting->description }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded {{ $setting->index ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $setting->index ? 'Index' : 'NoIndex' }}
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded {{ $setting->follow ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} ml-1">
                                                    {{ $setting->follow ? 'Follow' : 'NoFollow' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($setting->og_image)
                                                    <span class="text-green-600 text-sm">✓ Configurado</span>
                                                @else
                                                    <span class="text-gray-400 text-sm">Sin imagen</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('admin.seo.edit', $setting->id) }}"
                                                       class="text-brown hover:text-brown-dark font-semibold">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('admin.seo.destroy', $setting->id) }}" method="POST"
                                                          onsubmit="return confirm('¿Eliminar esta configuración SEO?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="p-4 border-t">
                            {{ $seoSettings->links() }}
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <p class="text-gray-500 text-lg">No hay configuraciones SEO creadas aún</p>
                            <a href="{{ route('admin.seo.create') }}" class="mt-4 inline-block px-6 py-3 bg-brown text-white rounded-lg font-bold hover:bg-brown-dark">
                                Crear primera configuración
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Páginas disponibles sin configurar -->
                <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-200">
                    <h3 class="text-lg font-bold text-blue-900 mb-3">Páginas Predefinidas Disponibles</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($availablePages as $key => $label)
                            @php
                                $exists = $seoSettings->where('page_key', $key)->count() > 0;
                            @endphp
                            <div class="px-4 py-2 bg-white rounded-lg text-sm {{ $exists ? 'opacity-50' : '' }}">
                                <span class="font-semibold text-gray-700">{{ $key }}</span>
                                <span class="text-gray-500 block text-xs">{{ $label }}</span>
                                @if($exists)
                                    <span class="text-green-600 text-xs">✓ Configurado</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
