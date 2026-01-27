<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'plan_id',
        'paypal_subscription_id',
        'paypal_transaction_id',
        'paypal_plan_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'type',
        'payer_email',
        'payer_name',
        'description',
        'paypal_response',
        'error_message',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paypal_response' => 'array',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la suscripción
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    /**
     * Relación con el plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Crear log de pago exitoso
     */
    public static function logSuccess(array $data): self
    {
        return self::create(array_merge($data, ['status' => 'completed']));
    }

    /**
     * Crear log de pago pendiente
     */
    public static function logPending(array $data): self
    {
        return self::create(array_merge($data, ['status' => 'pending']));
    }

    /**
     * Crear log de pago fallido
     */
    public static function logFailed(array $data): self
    {
        return self::create(array_merge($data, ['status' => 'failed']));
    }

    /**
     * Scope para pagos completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para pagos de un usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Obtener el nombre del estado formateado
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'completed' => 'Completado',
            'failed' => 'Fallido',
            'refunded' => 'Reembolsado',
            default => ucfirst($this->status),
        };
    }

    /**
     * Obtener el color del badge según el estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'completed' => 'green',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray',
        };
    }
}
