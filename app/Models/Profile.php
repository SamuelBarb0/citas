<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use App\Models\Like;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'edad',
        'genero',
        'orientacion_sexual',
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

    /**
     * Likes que este perfil ha RECIBIDO (otros usuarios le dieron like)
     */
    public function likedBy()
    {
        return $this->hasMany(Like::class, 'liked_user_id', 'user_id');
    }

    /**
     * Likes que han sido DADOS A este perfil por un usuario específico
     * Usada para filtrar perfiles ya likeados en el dashboard
     */
    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'liked_user_id', 'user_id');
    }

    /**
     * Obtener la URL completa de la foto principal
     */
    protected function fotoPrincipal(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (empty($value)) {
                    return asset('images/default-avatar.png');
                }

                // Si ya es una URL completa (http/https), devolverla tal cual
                if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                    return $value;
                }

                // Si es una ruta local, agregar /storage/
                return asset('storage/' . $value);
            }
        );
    }

    /**
     * Obtener las URLs completas de las fotos adicionales
     */
    protected function fotosAdicionales(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $fotos = is_string($value) ? json_decode($value, true) : $value;

                if (empty($fotos) || !is_array($fotos)) {
                    return [];
                }

                return array_map(function ($foto) {
                    // Si ya es una URL completa, devolverla tal cual
                    if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
                        return $foto;
                    }

                    // Si es una ruta local, agregar /storage/
                    return asset('storage/' . $foto);
                }, $fotos);
            }
        );
    }
}
