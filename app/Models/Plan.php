<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio_mensual',
        'precio_anual',
        'stripe_price_id_monthly',
        'stripe_price_id_yearly',
        'paypal_plan_id_monthly',
        'paypal_plan_id_yearly',
        'likes_diarios',
        'super_likes_mes',
        'ver_quien_te_gusta',
        'matches_ilimitados',
        'puede_iniciar_conversacion',
        'mensajes_semanales_gratis',
        'mensajes_ilimitados',
        'rewind',
        'boost_mensual',
        'sin_anuncios',
        'modo_incognito',
        'verificacion_prioritaria',
        'fotos_adicionales',
        'activo',
        'orden',
    ];

    protected $casts = [
        'precio_mensual' => 'decimal:2',
        'precio_anual' => 'decimal:2',
        'ver_quien_te_gusta' => 'boolean',
        'matches_ilimitados' => 'boolean',
        'puede_iniciar_conversacion' => 'boolean',
        'mensajes_ilimitados' => 'boolean',
        'rewind' => 'boolean',
        'boost_mensual' => 'boolean',
        'sin_anuncios' => 'boolean',
        'modo_incognito' => 'boolean',
        'verificacion_prioritaria' => 'boolean',
        'activo' => 'boolean',
    ];

    /**
     * Relación con suscripciones de usuarios
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Obtener el precio con descuento anual (ahorro)
     */
    public function getAhorroAnualAttribute()
    {
        $precioMensualAnualizado = $this->precio_mensual * 12;
        return $precioMensualAnualizado - $this->precio_anual;
    }

    /**
     * Obtener el porcentaje de descuento anual
     */
    public function getDescuentoAnualAttribute()
    {
        if ($this->precio_mensual == 0) return 0;
        $precioMensualAnualizado = $this->precio_mensual * 12;
        return round((($precioMensualAnualizado - $this->precio_anual) / $precioMensualAnualizado) * 100);
    }

    /**
     * Verificar si el plan es gratuito
     */
    public function isFree()
    {
        return $this->precio_mensual == 0 && $this->precio_anual == 0;
    }

    /**
     * Scope para obtener solo planes activos
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden');
    }
}
