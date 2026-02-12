@extends('layouts.app')

@section('content')
<div class="fixed inset-0 bg-gradient-to-br from-cream via-white to-cream flex flex-col overflow-hidden">
    <!-- Header fijo -->
    <div class="flex-shrink-0 bg-gradient-to-r from-teal-500 to-teal-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-white/80 hover:text-white transition p-2 rounded-full hover:bg-white/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-white">📄 Textos de la Página</h1>
                    <p class="text-white/90 text-lg mt-1">Cambiar los textos de inicio y otras secciones (sin código)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido con scroll -->
    <div class="flex-1 overflow-y-auto py-8" style="padding-bottom: 5rem;">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @php
                $sectionNames = [
                    'hero' => 'Sección Principal (Hero)',
                    'profiles' => 'Sección Perfiles',
                    'features' => 'Cómo Funciona',
                    'safety' => 'Consejos de Seguridad',
                    'cta' => 'Llamada a la Acción',
                    'footer' => 'Pie de Página',
                    'general' => 'General',
                ];
            @endphp

            @forelse($sections as $sectionKey => $contents)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-brown mb-6 pb-3 border-b border-gray-200 flex items-center gap-2">
                        <span class="w-8 h-8 bg-gradient-to-br from-heart-red to-heart-red-light rounded-lg flex items-center justify-center text-white text-sm">
                            @if($sectionKey === 'hero')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            @elseif($sectionKey === 'features')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            @elseif($sectionKey === 'cta')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </span>
                        {{ $sectionNames[$sectionKey] ?? ucfirst($sectionKey) }}
                    </h2>

                    <div class="space-y-6">
                        @foreach($contents as $content)
                            <div class="group">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="content-{{ $content->key }}" class="block text-sm font-semibold text-gray-700">
                                        {{ $content->label }}
                                    </label>
                                    @if($content->value !== null && $content->value !== $content->default_value)
                                        <a href="{{ route('admin.content.reset', $content->key) }}"
                                           class="text-xs text-gray-500 hover:text-heart-red transition flex items-center gap-1"
                                           onclick="return confirm('¿Restablecer al valor por defecto?')">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Restablecer
                                        </a>
                                    @endif
                                </div>

                                @if($content->type === 'text')
                                    <input type="text"
                                           id="content-{{ $content->key }}"
                                           name="contents[{{ $content->key }}]"
                                           value="{{ $content->value ?? $content->default_value }}"
                                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none transition"
                                           placeholder="{{ $content->default_value }}">

                                @elseif($content->type === 'textarea')
                                    <textarea id="content-{{ $content->key }}"
                                              name="contents[{{ $content->key }}]"
                                              rows="3"
                                              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none transition resize-none"
                                              placeholder="{{ $content->default_value }}">{{ $content->value ?? $content->default_value }}</textarea>

                                @elseif($content->type === 'image')
                                    <div class="flex items-center gap-4">
                                        @if($content->value)
                                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                                <img src="{{ Storage::url($content->value) }}" alt="{{ $content->label }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <input type="file"
                                                   id="content-{{ $content->key }}"
                                                   name="contents[{{ $content->key }}]"
                                                   accept="image/*"
                                                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-heart-red/10 file:text-heart-red hover:file:bg-heart-red/20">
                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG (máx. 2MB)</p>
                                        </div>
                                    </div>

                                @elseif($content->type === 'color')
                                    <div class="flex items-center gap-3">
                                        <input type="color"
                                               id="content-{{ $content->key }}"
                                               name="contents[{{ $content->key }}]"
                                               value="{{ $content->value ?? $content->default_value }}"
                                               class="w-16 h-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                                        <input type="text"
                                               value="{{ $content->value ?? $content->default_value }}"
                                               class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-heart-red focus:outline-none transition"
                                               onchange="document.getElementById('content-{{ $content->key }}').value = this.value"
                                               oninput="document.getElementById('content-{{ $content->key }}').value = this.value">
                                    </div>
                                @endif

                                @if($content->default_value && $content->type !== 'image')
                                    <p class="text-xs text-gray-400 mt-1">Por defecto: {{ Str::limit($content->default_value, 100) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">No hay contenidos configurados</h3>
                    <p class="text-gray-500 mb-4">Ejecuta el seeder para cargar los contenidos por defecto.</p>
                    <code class="bg-gray-100 px-4 py-2 rounded-lg text-sm text-gray-700">php artisan db:seed --class=SiteContentSeeder</code>
                </div>
            @endforelse

            @if($sections->count() > 0)
                <div class="flex justify-end gap-4">
                    <a href="{{ url('/') }}" target="_blank" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver Página
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-heart-red to-heart-red-light text-white rounded-xl font-bold hover:shadow-glow transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            @endif
        </form>
        </div>
    </div>
</div>
@endsection
