<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SelectOption extends Model
{
    protected $fillable = [
        'tipo',
        'valor',
        'etiqueta',
        'grupo',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Obtener opciones por tipo con caché
     */
    public static function getByType($tipo)
    {
        return Cache::remember("select_options_{$tipo}", 3600, function () use ($tipo) {
            return self::where('tipo', $tipo)
                ->where('activo', true)
                ->orderBy('orden')
                ->orderBy('etiqueta')
                ->get();
        });
    }

    /**
     * Obtener opciones agrupadas por tipo
     */
    public static function getGroupedByType($tipo)
    {
        $options = self::getByType($tipo);

        if ($options->isEmpty()) {
            return [];
        }

        // Si no hay grupos (null o vacío), devolver sin agrupar
        $tieneGrupos = $options->filter(function ($opt) {
            return !empty($opt->grupo);
        })->isNotEmpty();

        if (!$tieneGrupos) {
            return ['Sin grupo' => $options];
        }

        // Agrupar, poniendo los sin grupo bajo 'Sin grupo'
        return $options->groupBy(function ($opt) {
            return empty($opt->grupo) ? 'Sin grupo' : $opt->grupo;
        });
    }

    /**
     * Limpiar caché al guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($option) {
            Cache::forget("select_options_{$option->tipo}");
        });

        static::deleted(function ($option) {
            Cache::forget("select_options_{$option->tipo}");
        });
    }
}
