<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    protected $fillable = [
        'key',
        'section',
        'label',
        'type',
        'value',
        'default_value',
        'order',
    ];

    /**
     * Obtener un contenido por su clave
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("site_content.{$key}", 3600, function () use ($key, $default) {
            $content = static::where('key', $key)->first();
            return $content ? ($content->value ?? $content->default_value ?? $default) : $default;
        });
    }

    /**
     * Obtener todos los contenidos de una sección
     */
    public static function getSection(string $section): array
    {
        return Cache::remember("site_content.section.{$section}", 3600, function () use ($section) {
            return static::where('section', $section)
                ->orderBy('order')
                ->get()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Limpiar caché al guardar
     */
    protected static function booted()
    {
        static::saved(function ($content) {
            Cache::forget("site_content.{$content->key}");
            Cache::forget("site_content.section.{$content->section}");
        });
    }
}
