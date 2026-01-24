<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'last_activity',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'last_activity' => 'datetime',
        ];
    }

    // Relaciones
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedBy()
    {
        return $this->hasMany(Like::class, 'liked_user_id');
    }

    public function matches()
    {
        return $this->hasMany(UserMatch::class, 'user_id_1')
            ->orWhere('user_id_2', $this->id);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('estado', 'activa')
            ->where('fecha_expiracion', '>', now())
            ->latest();
    }

    /**
     * Obtener el plan actual del usuario
     */
    public function currentPlan()
    {
        $subscription = $this->activeSubscription;
        return $subscription ? $subscription->plan : null;
    }

    /**
     * Verificar si tiene un plan premium activo
     */
    public function hasPremium()
    {
        return $this->activeSubscription !== null;
    }

    /**
     * Verificar si puede dar like (según límites del plan)
     */
    public function canGiveLike()
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            // Plan gratuito por defecto - 10 likes diarios
            return true; // Implementar lógica de límite gratuito
        }

        return $subscription->canLike();
    }

    /**
     * Verificar si el usuario está online (activo en los últimos 5 minutos)
     */
    public function isOnline()
    {
        if (!$this->last_activity) {
            return false;
        }

        return $this->last_activity->diffInMinutes(now()) < 5;
    }

    /**
     * Obtener el límite de fotos adicionales según el plan
     * Plan gratuito = 6 fotos, planes premium pueden tener más
     */
    public function getMaxFotosAdicionales()
    {
        $plan = $this->currentPlan();

        if ($plan && $plan->fotos_adicionales > 0) {
            return $plan->fotos_adicionales;
        }

        return 6; // Límite por defecto para plan gratuito
    }

    /**
     * Verificar si puede ver quién le dio like
     */
    public function canSeeWhoLikedMe()
    {
        $plan = $this->currentPlan();

        return $plan && $plan->ver_quien_te_gusta;
    }
}
