<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'edad',
        'genero',
        'busco',
        'biografia',
        'ciudad',
        'foto_principal',
        'fotos_adicionales',
        'intereses',
        'activo',
        'verified',
        'verified_at',
    ];

    protected $casts = [
        'fotos_adicionales' => 'array',
        'intereses' => 'array',
        'activo' => 'boolean',
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy()
    {
        return $this->hasMany(Like::class, 'liked_user_id', 'user_id');
    }
}
