<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    protected $fillable = [
        'user_id',
        'profile_id',
        'verification_photo',
        'estado',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que solicita verificación
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el perfil
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Relación con el admin que revisó
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope para solicitudes pendientes
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para solicitudes aprobadas
     */
    public function scopeAprobada($query)
    {
        return $query->where('estado', 'aprobada');
    }

    /**
     * Scope para solicitudes rechazadas
     */
    public function scopeRechazada($query)
    {
        return $query->where('estado', 'rechazada');
    }

    /**
     * Verificar si está pendiente
     */
    public function isPendiente()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si fue aprobada
     */
    public function isAprobada()
    {
        return $this->estado === 'aprobada';
    }

    /**
     * Verificar si fue rechazada
     */
    public function isRechazada()
    {
        return $this->estado === 'rechazada';
    }
}
