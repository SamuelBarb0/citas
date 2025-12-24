<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use App\Models\UserSubscription;

class AssignPlansToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = Plan::all();
        $planFree = $plans->where('slug', 'free')->first();
        $planBasico = $plans->where('slug', 'basico')->first();
        $planPremium = $plans->where('slug', 'premium')->first();

        // Obtener todos los usuarios (excepto admin si existe)
        $users = User::where('is_admin', false)->get();

        if ($users->count() < 3) {
            $this->command->warn('Se necesitan al menos 3 usuarios para probar. Solo hay ' . $users->count());
            return;
        }

        // Distribuir planes entre los usuarios
        $usersArray = $users->toArray();
        shuffle($usersArray); // Mezclar aleatoriamente

        $distribution = [
            'free' => [],
            'basico' => [],
            'premium' => [],
        ];

        // Dividir usuarios en 3 grupos aproximadamente iguales
        $totalUsers = count($usersArray);
        $usersPerPlan = ceil($totalUsers / 3);

        foreach ($usersArray as $index => $userData) {
            $user = User::find($userData['id']);

            if ($index < $usersPerPlan) {
                // Usuarios Gratis (sin suscripción activa)
                $distribution['free'][] = $user->name;
                $this->command->info("👤 {$user->name} - Plan Gratis (sin suscripción)");

            } elseif ($index < $usersPerPlan * 2) {
                // Usuarios Básico
                UserSubscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'plan_id' => $planBasico->id,
                        'tipo' => 'mensual',
                        'estado' => 'activa',
                        'fecha_inicio' => now(),
                        'fecha_expiracion' => now()->addMonth(),
                        'monto_pagado' => $planBasico->precio_mensual,
                        'mensajes_enviados_esta_semana' => 0,
                        'ultimo_reset_mensajes' => now(),
                    ]
                );
                $distribution['basico'][] = $user->name;
                $this->command->info("⭐ {$user->name} - Plan Básico (3 mensajes/semana a Gratis, ilimitados a Básico/Premium)");

            } else {
                // Usuarios Premium
                UserSubscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'plan_id' => $planPremium->id,
                        'tipo' => 'mensual',
                        'estado' => 'activa',
                        'fecha_inicio' => now(),
                        'fecha_expiracion' => now()->addMonth(),
                        'monto_pagado' => $planPremium->precio_mensual,
                        'mensajes_enviados_esta_semana' => 0,
                        'ultimo_reset_mensajes' => now(),
                    ]
                );
                $distribution['premium'][] = $user->name;
                $this->command->info("💎 {$user->name} - Plan Premium (mensajes ilimitados con todos)");
            }
        }

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📊 RESUMEN DE DISTRIBUCIÓN:');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('👤 Plan Gratis: ' . count($distribution['free']) . ' usuarios');
        $this->command->info('⭐ Plan Básico: ' . count($distribution['basico']) . ' usuarios');
        $this->command->info('💎 Plan Premium: ' . count($distribution['premium']) . ' usuarios');
        $this->command->newLine();
        $this->command->info('🎉 ¡Planes asignados exitosamente!');
        $this->command->newLine();
        $this->command->warn('⚠️  IMPORTANTE: Reglas de mensajería:');
        $this->command->warn('   • Gratis: Solo pueden RESPONDER mensajes (no iniciar)');
        $this->command->warn('   • Básico: 3 mensajes/semana a Gratis + ilimitados a Básico/Premium');
        $this->command->warn('   • Premium: Mensajes ilimitados con TODOS');
    }
}
