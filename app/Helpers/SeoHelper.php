<?php

namespace App\Helpers;

use App\Models\SeoSetting;
use Illuminate\Support\Facades\Route;

class SeoHelper
{
    /**
     * Obtiene la configuración SEO para la ruta actual
     */
    public static function getCurrentSeoSettings(): ?SeoSetting
    {
        $routeName = Route::currentRouteName();
        $pageKey = self::getPageKeyFromRoute($routeName);

        return SeoSetting::getOrDefault($pageKey);
    }

    /**
     * Mapea el nombre de la ruta a una clave de página
     */
    private static function getPageKeyFromRoute(?string $routeName): string
    {
        if (!$routeName) {
            return 'home';
        }

        $mapping = [
            'dashboard' => 'dashboard',
            'matches' => 'matches',
            'messages' => 'messages',
            'messages.show' => 'messages',
            'user.profile.show' => 'profile',
            'user.profile.edit' => 'profile',
            'subscriptions.index' => 'plans',
            'subscriptions.checkout' => 'plans',
            'login' => 'login',
            'register' => 'register',
        ];

        return $mapping[$routeName] ?? 'home';
    }

    /**
     * Genera las meta tags HTML
     */
    public static function generateMetaTags(?SeoSetting $seo = null): string
    {
        if (!$seo) {
            $seo = self::getCurrentSeoSettings();
        }

        $tags = [];

        // Basic Meta Tags
        if ($seo->title) {
            $tags[] = '<title>' . e($seo->title) . '</title>';
            $tags[] = '<meta name="title" content="' . e($seo->title) . '">';
        }

        if ($seo->description) {
            $tags[] = '<meta name="description" content="' . e($seo->description) . '">';
        }

        if ($seo->keywords) {
            $tags[] = '<meta name="keywords" content="' . e($seo->keywords) . '">';
        }

        // Robots
        $robotsContent = [];
        $robotsContent[] = $seo->index ? 'index' : 'noindex';
        $robotsContent[] = $seo->follow ? 'follow' : 'nofollow';
        $tags[] = '<meta name="robots" content="' . implode(', ', $robotsContent) . '">';

        // Open Graph (Facebook, WhatsApp, LinkedIn)
        if ($seo->og_title) {
            $tags[] = '<meta property="og:title" content="' . e($seo->og_title) . '">';
        }

        if ($seo->og_description) {
            $tags[] = '<meta property="og:description" content="' . e($seo->og_description) . '">';
        }

        if ($seo->og_image) {
            $imageUrl = asset('storage/' . $seo->og_image);
            $tags[] = '<meta property="og:image" content="' . $imageUrl . '">';
            $tags[] = '<meta property="og:image:secure_url" content="' . $imageUrl . '">';
        }

        $tags[] = '<meta property="og:type" content="' . e($seo->og_type) . '">';
        $tags[] = '<meta property="og:url" content="' . url()->current() . '">';
        $tags[] = '<meta property="og:site_name" content="Citas Mallorca">';

        // Twitter Card
        $tags[] = '<meta name="twitter:card" content="' . e($seo->twitter_card) . '">';

        if ($seo->twitter_title) {
            $tags[] = '<meta name="twitter:title" content="' . e($seo->twitter_title) . '">';
        }

        if ($seo->twitter_description) {
            $tags[] = '<meta name="twitter:description" content="' . e($seo->twitter_description) . '">';
        }

        if ($seo->twitter_image) {
            $tags[] = '<meta name="twitter:image" content="' . asset('storage/' . $seo->twitter_image) . '">';
        }

        // Additional meta tags
        $tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $tags[] = '<meta charset="UTF-8">';

        return implode("\n    ", $tags);
    }

    /**
     * Genera el título de la página
     */
    public static function getPageTitle(?SeoSetting $seo = null): string
    {
        if (!$seo) {
            $seo = self::getCurrentSeoSettings();
        }

        return $seo->title ?? 'Citas Mallorca - Encuentra tu conexión en la isla';
    }
}
