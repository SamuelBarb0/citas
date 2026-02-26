<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Message;
use App\Models\UserMatch;

class VerifyMessagingRestrictions extends Command
{
    protected $signature = 'messaging:verify';

    protected $description = 'Verificar restricciones de mensajería por plan';

    public function handle()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('          VERIFICACIÓN DE RESTRICCIONES DE MENSAJERÍA');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('');

        $users = User::where('is_admin', false)
            ->with(['activeSubscription.plan', 'profile'])
            ->get();

        if ($users->count() === 0) {
            $this->error('No hay usuarios para verificar.');
            return;
        }

        $free = [];
        $paid = [];

        foreach ($users as $user) {
            $subscription = $user->activeSubscription;
            if ($subscription && $subscription->plan && $subscription->plan->puede_iniciar_conversacion) {
                $paid[] = $user;
            } else {
                $free[] = $user;
            }
        }

        // Mostrar usuarios gratuitos
        $this->info('👤 USUARIOS GRATUITOS (' . count($free) . '):');
        $this->info('─────────────────────────────────────────────────────────────────────');
        if (count($free) > 0) {
            foreach ($free as $user) {
                $this->line('  • ' . $user->name . ' (ID: ' . $user->id . ') - ' . $user->email);
            }
        } else {
            $this->line('  (ninguno)');
        }
        $this->info('');

        // Mostrar usuarios de pago
        $this->info('💎 USUARIOS DE PAGO (' . count($paid) . '):');
        $this->info('─────────────────────────────────────────────────────────────────────');
        if (count($paid) > 0) {
            foreach ($paid as $user) {
                $plan = $user->activeSubscription->plan;
                $tipo = $user->activeSubscription->tipo;
                $this->line('  • ' . $user->name . ' (ID: ' . $user->id . ') - ' . $plan->nombre . ' (' . $tipo . ') - Mensajes ilimitados');
            }
        } else {
            $this->line('  (ninguno)');
        }
        $this->info('');

        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('          REGLAS DE MENSAJERÍA');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('');
        $this->line('  👤 Gratis (sin suscripción):');
        $this->line('     • NO puede iniciar conversaciones');
        $this->line('     • Puede responder libremente en conversaciones iniciadas por otros');
        $this->line('');
        $this->line('  💎 De pago (Mensual/Anual):');
        $this->line('     • Mensajes ILIMITADOS con TODOS los usuarios');
        $this->line('     • Puede iniciar conversaciones');
        $this->info('');

        return 0;
    }
}
