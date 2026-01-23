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
     * Incluye suscripciones canceladas pero aún válidas hasta fin de período
     */
    public function isActive()
    {
        return in_array($this->estado, ['activa', 'cancelada_fin_periodo']) &&
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
            'boosts_restantes' => $this->plan->boost_mensual ? 1 : 0,
            'ultimo_reset_likes' => now(),
            'mensajes_enviados_esta_semana' => 0,
            'ultimo_reset_mensajes' => now(),
        ]);
    }

    /**
     * Marcar suscripción como impagada (fallo de cobro)
     * Bloquea automáticamente el acceso del usuario
     */
    public function markAsUnpaid()
    {
        $this->update([
            'estado' => 'impago',
            'auto_renovacion' => false,
        ]);

        // Bloquear acceso del usuario
        $this->user->update(['activo' => false]);
    }

    /**
     * Reactivar suscripción tras regularizar el pago
     * Restaura el acceso automáticamente
     */
    public function reactivate()
    {
        $duracionMeses = $this->tipo === 'anual' ? 12 : 1;

        $this->update([
            'estado' => 'activa',
            'fecha_expiracion' => now()->addMonths($duracionMeses),
            'auto_renovacion' => true,
        ]);

        // Restaurar acceso del usuario
        $this->user->update(['activo' => true]);
    }

    /**
     * Renovar suscripción (cobro recurrente exitoso)
     */
    public function renew($transactionId = null, $montoPagado = null)
    {
        $duracionMeses = $this->tipo === 'anual' ? 12 : 1;

        $updateData = [
            'estado' => 'activa',
            'fecha_expiracion' => now()->addMonths($duracionMeses),
            'boosts_restantes' => $this->plan->boost_mensual ? 1 : 0,
        ];

        if ($transactionId) {
            $updateData['transaction_id'] = $transactionId;
        }

        if ($montoPagado) {
            $updateData['monto_pagado'] = $montoPagado;
        }

        $this->update($updateData);

        // Asegurar que el usuario tenga acceso
        $this->user->update(['activo' => true]);
    }

    /**
     * Verificar si la suscripción está impagada
     */
    public function isUnpaid()
    {
        return $this->estado === 'impago';
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
     * - Gratis: NO puede iniciar conversaciones. Solo puede responder 1 mensaje por cada mensaje recibido
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
            // Contar mensajes en la conversación
            $messagesReceived = \App\Models\Message::where('sender_id', $receiverUser->id)
                ->where('receiver_id', $this->user_id)
                ->count();

            $messagesSent = \App\Models\Message::where('sender_id', $this->user_id)
                ->where('receiver_id', $receiverUser->id)
                ->count();

            // Solo puede responder si ha recibido más mensajes de los que ha enviado
            return $messagesSent < $messagesReceived;
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
