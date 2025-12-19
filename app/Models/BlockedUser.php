<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $fillable = [
        'user_id',
        'blocked_user_id',
        'reason',
    ];

    // Relación con el usuario que bloquea
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el usuario bloqueado
    public function blockedUser()
    {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }
}
