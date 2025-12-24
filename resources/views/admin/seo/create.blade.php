<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-brown to-brown-dark shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-white">Nueva Configuración SEO</h1>
                        <p class="text-white/80 mt-1">Crea meta tags para una nueva página</p>
                    </div>
                    <a href="{{ route('admin.seo.index') }}"
                       class="px-4 py-2 bg-white/20 text-white rounded-lg font-semibold hover:bg-white/30">
                        ← Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <form action="{{ route('admin.seo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Page Key -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Identificador de Página</h2>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Page Key (Slug único)</label>
                            <input type="text" name="page_key" value="{{ old('page_key') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown"
                                   placeholder="Ej: home, dashboard, contact">
                            <p class="text-xs text-gray-500 mt-1">Identificador único para esta página (ej: "home", "dashboard", "about")</p>
                            @error('page_key')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Meta Tags Básicos -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Meta Tags Básicos</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Título de la Página</label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown"
                                       placeholder="Ej: Citas Mallorca - Encuentra el amor en la isla">
                                <p class="text-xs text-gray-500 mt-1">Recomendado: 50-60 caracteres</p>
                                @error('title')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Meta Description</label>
                                <textarea name="description" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown"
                                          placeholder="Descripción breve de la página para buscadores">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Recomendado: 150-160 caracteres</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Keywords (separadas por comas)</label>
                                <input type="text" name="keywords" value="{{ old('keywords') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown"
                                       placeholder="citas, mallorca, dating, amor, relaciones">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="hidden" name="index" value="0">
                                    <input type="checkbox" name="index" value="1" {{ old('index', true) ? 'checked' : '' }}
                                           class="w-5 h-5 text-brown border-gray-300 rounded focus:ring-brown">
                                    <span class="text-sm font-semibold text-gray-700">Permitir Index (Google)</span>
                                </label>

                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="hidden" name="follow" value="0">
                                    <input type="checkbox" name="follow" value="1" {{ old('follow', true) ? 'checked' : '' }}
                                           class="w-5 h-5 text-brown border-gray-300 rounded focus:ring-brown">
                                    <span class="text-sm font-semibold text-gray-700">Permitir Follow (Enlaces)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Open Graph (Facebook) -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Open Graph (Facebook / WhatsApp)</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">OG Title</label>
                                <input type="text" name="og_title" value="{{ old('og_title') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">OG Description</label>
                                <textarea name="og_description" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">{{ old('og_description') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">OG Image</label>
                                <input type="file" name="og_image" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">
                                <p class="text-xs text-gray-500 mt-1">Recomendado: 1200x630px</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">OG Type</label>
                                <select name="og_type"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">
                                    <option value="website">Website</option>
                                    <option value="article">Article</option>
                                    <option value="profile">Profile</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Twitter Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Twitter Card</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Twitter Card Type</label>
                                <select name="twitter_card"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">
                                    <option value="summary">Summary</option>
                                    <option value="summary_large_image" selected>Summary Large Image</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Twitter Title</label>
                                <input type="text" name="twitter_title" value="{{ old('twitter_title') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Twitter Description</label>
                                <textarea name="twitter_description" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">{{ old('twitter_description') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Twitter Image</label>
                                <input type="file" name="twitter_image" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brown focus:border-brown">
                                <p class="text-xs text-gray-500 mt-1">Recomendado: 1200x675px</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.seo.index') }}"
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-brown text-white rounded-lg font-bold hover:bg-brown-dark">
                            Crear Configuración SEO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
