<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'liked_user_id',
        'is_super_like',
    ];

    protected $casts = [
        'is_super_like' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedUser()
    {
        return $this->belongsTo(User::class, 'liked_user_id');
    }
}
