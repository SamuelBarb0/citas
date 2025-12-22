<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'tipo',
        'estado',
        'fecha_inicio',
        'fecha_expiracion',
        'auto_renovacion',
        'metodo_pago',
        'stripe_subscription_id',
        'paypal_subscription_id',
        'transaction_id',
        'monto_pagado',
        'likes_usados_hoy',
        'ultimo_reset_likes',
        'super_likes_restantes',
        'boosts_restantes',
        'ultimo_boost',
        'mensajes_enviados_esta_semana',
        'ultimo_reset_mensajes',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_expiracion' => 'date',
        'auto_renovacion' => 'boolean',
        'monto_pagado' => 'decimal:2',
        'ultimo_reset_likes' => 'date',
        'ultimo_boost' => 'date',
        'ultimo_reset_mensajes' => 'date',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Verificar si la suscripción está activa
     */
    public function isActive()
    {
        return $this->estado === 'activa' &&
               $this->fecha_expiracion &&
               $this->fecha_expiracion->isFuture();
    }

    /**
     * Verificar si la suscripción ha expirado
     */
    public function hasExpired()
    {
        return $this->fecha_expiracion && $this->fecha_expiracion->isPast();
    }

    /**
     * Obtener días restantes
     */
    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_expiracion) return 0;
        return max(0, now()->diffInDays($this->fecha_expiracion, false));
    }

    /**
     * Resetear contador de likes diarios
     */
    public function resetDailyLikes()
    {
        if (!$this->ultimo_reset_likes || $this->ultimo_reset_likes->isToday() === false) {
            $this->update([
                'likes_usados_hoy' => 0,
                'ultimo_reset_likes' => now(),
            ]);
        }
    }

    /**
     * Verificar si puede dar más likes hoy
     */
    public function canLike()
    {
        $this->resetDailyLikes();

        if ($this->plan->likes_diarios === -1) {
            return true; // Ilimitado
        }

        return $this->likes_usados_hoy < $this->plan->likes_diarios;
    }

    /**
     * Incrementar contador de likes
     */
    public function incrementLikes()
    {
        $this->resetDailyLikes();
        $this->increment('likes_usados_hoy');
    }

    /**
     * Verificar si tiene super likes disponibles
     */
    public function hasSuperLikes()
    {
        return $this->super_likes_restantes > 0;
    }

    /**
     * Usar un super like
     */
    public function useSuperLike()
    {
        if ($this->super_likes_restantes > 0) {
            $this->decrement('super_likes_restantes');
            return true;
        }
        return false;
    }

    /**
     * Verificar si tiene boosts disponibles
     */
    public function hasBoosts()
    {
        return $this->boosts_restantes > 0;
    }

    /**
     * Usar un boost
     */
    public function useBoost()
    {
        if ($this->boosts_restantes > 0) {
            $this->update([
                'boosts_restantes' => $this->boosts_restantes - 1,
                'ultimo_boost' => now(),
            ]);
            return true;
        }
        return false;
    }

    /**
     * Scope para suscripciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('estado', 'activa')
                     ->where('fecha_expiracion', '>', now());
    }

    /**
     * Scope para suscripciones expiradas
     */
    public function scopeExpired($query)
    {
        return $query->where('estado', 'activa')
                     ->where('fecha_expiracion', '<=', now());
    }

    /**
     * Cancelar suscripción
     */
    public function cancel()
    {
        $this->update([
            'estado' => 'cancelada',
            'auto_renovacion' => false,
        ]);
    }

    /**
     * Activar suscripción
     */
    public function activate()
    {
        $duracionMeses = $this->tipo === 'anual' ? 12 : 1;

        $this->update([
            'estado' => 'activa',
            'fecha_inicio' => now(),
            'fecha_expiracion' => now()->addMonths($duracionMeses),
            'super_likes_restantes' => $this->plan->super_likes_mes,
            'boosts_restantes' => $this->plan->boost_mensual ? 1 : 0,
            'ultimo_reset_likes' => now(),
            'mensajes_enviados_esta_semana' => 0,
            'ultimo_reset_mensajes' => now(),
        ]);
    }

    /**
     * Resetear contador de mensajes semanales
     */
    public function resetWeeklyMessages()
    {
        if (!$this->ultimo_reset_mensajes || $this->ultimo_reset_mensajes->diffInDays(now()) >= 7) {
            $this->update([
                'mensajes_enviados_esta_semana' => 0,
                'ultimo_reset_mensajes' => now(),
            ]);
        }
    }

    /**
     * Verificar si puede enviar mensaje a un usuario específico
     * Reglas:
     * - Gratis: NO puede iniciar conversaciones (solo responder)
     * - Básico: 3 mensajes/semana a usuarios Gratis, ilimitados a Básico/Premium
     * - Premium: Ilimitados con todos
     */
    public function canSendMessageTo($receiverUser)
    {
        $this->resetWeeklyMessages();

        // Si el plan tiene mensajes ilimitados (Premium)
        if ($this->plan->mensajes_ilimitados) {
            return true;
        }

        // Si el plan NO puede iniciar conversaciones (Gratis)
        if (!$this->plan->puede_iniciar_conversacion) {
            return false;
        }

        // Plan Básico
        // Obtener el plan del receptor
        $receiverSubscription = $receiverUser->activeSubscription;
        $receiverPlan = $receiverSubscription ? $receiverSubscription->plan : null;

        // Si el receptor es Gratis, verificar límite semanal
        if (!$receiverPlan || $receiverPlan->slug === 'free') {
            return $this->mensajes_enviados_esta_semana < $this->plan->mensajes_semanales_gratis;
        }

        // Mensajes ilimitados entre Básico y Premium
        return true;
    }

    /**
     * Incrementar contador de mensajes semanales a usuarios gratis
     */
    public function incrementWeeklyMessages($receiverUser)
    {
        $this->resetWeeklyMessages();

        // Solo contar mensajes a usuarios gratis
        $receiverSubscription = $receiverUser->activeSubscription;
        $receiverPlan = $receiverSubscription ? $receiverSubscription->plan : null;

        if (!$receiverPlan || $receiverPlan->slug === 'free') {
            $this->increment('mensajes_enviados_esta_semana');
        }
    }

    /**
     * Obtener mensajes restantes esta semana a usuarios gratis
     */
    public function getRemainingWeeklyMessages()
    {
        $this->resetWeeklyMessages();

        if ($this->plan->mensajes_ilimitados) {
            return -1; // Ilimitado
        }

        if (!$this->plan->puede_iniciar_conversacion) {
            return 0; // No puede enviar
        }

        return max(0, $this->plan->mensajes_semanales_gratis - $this->mensajes_enviados_esta_semana);
    }
}
