<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_key',
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'index',
        'follow',
        'custom_meta',
    ];

    protected $casts = [
        'index' => 'boolean',
        'follow' => 'boolean',
        'custom_meta' => 'array',
    ];

    /**
     * Obtener configuración SEO por clave de página
     */
    public static function getByPageKey(string $pageKey): ?self
    {
        return self::where('page_key', $pageKey)->first();
    }

    /**
     * Obtener o crear configuración SEO por defecto
     */
    public static function getOrDefault(string $pageKey): self
    {
        $setting = self::getByPageKey($pageKey);

        if (!$setting) {
            $setting = new self([
                'page_key' => $pageKey,
                'title' => 'Citas Mallorca - Encuentra tu conexión en la isla',
                'description' => 'La mejor plataforma de citas en Mallorca. Conoce personas auténticas y encuentra conexiones reales.',
                'og_type' => 'website',
                'twitter_card' => 'summary_large_image',
                'index' => true,
                'follow' => true,
            ]);
        }

        return $setting;
    }
}
