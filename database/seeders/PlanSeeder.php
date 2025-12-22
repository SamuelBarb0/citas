<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'nombre' => 'Gratis',
                'slug' => 'free',
                'descripcion' => 'Solo puedes responder mensajes',
                'precio_mensual' => 0.00,
                'precio_anual' => 0.00,
                'likes_diarios' => -1,
                'super_likes_mes' => 0,
                'ver_quien_te_gusta' => false,
                'matches_ilimitados' => true,
                'puede_iniciar_conversacion' => false, // NO puede enviar mensajes, solo responder
                'mensajes_semanales_gratis' => 0,
                'mensajes_ilimitados' => false,
                'rewind' => false,
                'boost_mensual' => false,
                'sin_anuncios' => false,
                'modo_incognito' => false,
                'verificacion_prioritaria' => false,
                'fotos_adicionales' => 10,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Básico',
                'slug' => 'basico',
                'descripcion' => '3 mensajes/semana a usuarios gratis + ilimitados entre Básico y Premium',
                'precio_mensual' => 4.99,
                'precio_anual' => 39.99,
                'likes_diarios' => -1,
                'super_likes_mes' => 0,
                'ver_quien_te_gusta' => false,
                'matches_ilimitados' => true,
                'puede_iniciar_conversacion' => true, // Puede enviar mensajes
                'mensajes_semanales_gratis' => 3, // 3 mensajes por semana a usuarios Gratis
                'mensajes_ilimitados' => false, // Ilimitados entre Básico y Premium
                'rewind' => false,
                'boost_mensual' => false,
                'sin_anuncios' => false,
                'modo_incognito' => false,
                'verificacion_prioritaria' => false,
                'fotos_adicionales' => 10,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Premium',
                'slug' => 'premium',
                'descripcion' => 'Mensajes ilimitados con todos los usuarios',
                'precio_mensual' => 9.99,
                'precio_anual' => 79.99,
                'likes_diarios' => -1,
                'super_likes_mes' => 0,
                'ver_quien_te_gusta' => false,
                'matches_ilimitados' => true,
                'puede_iniciar_conversacion' => true, // Puede enviar mensajes
                'mensajes_semanales_gratis' => -1, // Sin límite
                'mensajes_ilimitados' => true, // Mensajes ilimitados con todos
                'rewind' => false,
                'boost_mensual' => false,
                'sin_anuncios' => false,
                'modo_incognito' => false,
                'verificacion_prioritaria' => false,
                'fotos_adicionales' => 10,
                'activo' => true,
                'orden' => 3,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }

        $this->command->info('Planes creados exitosamente!');
    }
}
