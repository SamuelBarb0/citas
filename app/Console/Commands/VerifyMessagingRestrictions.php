<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Plan;

class VerifyMessagingRestrictions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messaging:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar restricciones de mensajería por plan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('          VERIFICACIÓN DE RESTRICCIONES DE MENSAJERÍA');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('');

        // Obtener todos los usuarios no admin
        $users = User::where('is_admin', false)
            ->with(['activeSubscription.plan', 'profile'])
            ->get();

        if ($users->count() === 0) {
            $this->error('No hay usuarios para verificar.');
            return;
        }

        // Agrupar por plan
        $usersByPlan = [
            'free' => [],
            'basico' => [],
            'premium' => [],
        ];

        foreach ($users as $user) {
            $subscription = $user->activeSubscription;
            $planSlug = $subscription ? $subscription->plan->slug : 'free';
            $usersByPlan[$planSlug][] = $user;
        }

        // Mostrar usuarios por plan
        $this->showUsersByPlan($usersByPlan);

        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('          MATRIZ DE RESTRICCIONES');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('');

        // Crear matriz de permisos
        $this->createPermissionMatrix($usersByPlan);

        $this->info('');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('          REGLAS DE MENSAJERÍA');
        $this->info('═══════════════════════════════════════════════════════════════════════');
        $this->info('');
        $this->line('  👤 Plan Gratis:');
        $this->line('     • NO puede iniciar conversaciones (solo responder)');
        $this->line('     • Debe esperar a que otros usuarios le escriban primero');
        $this->line('');
        $this->line('  ⭐ Plan Básico (€4.99/mes):');
        $this->line('     • Puede enviar 3 mensajes/semana a usuarios Gratis');
        $this->line('     • Mensajes ILIMITADOS con usuarios Básico y Premium');
        $this->line('');
        $this->line('  💎 Plan Premium (€9.99/mes):');
        $this->line('     • Mensajes ILIMITADOS con TODOS los usuarios');
        $this->line('     • Sin restricciones de ningún tipo');
        $this->info('');

        return 0;
    }

    private function showUsersByPlan($usersByPlan)
    {
        // Plan Gratis
        $this->info('👤 USUARIOS CON PLAN GRATIS (' . count($usersByPlan['free']) . '):');
        $this->info('─────────────────────────────────────────────────────────────────────');
        if (count($usersByPlan['free']) > 0) {
            foreach ($usersByPlan['free'] as $user) {
                $this->line('  • ' . $user->name . ' (ID: ' . $user->id . ') - ' . $user->email);
            }
        } else {
            $this->line('  (ninguno)');
        }
        $this->info('');

        // Plan Básico
        $this->info('⭐ USUARIOS CON PLAN BÁSICO (' . count($usersByPlan['basico']) . '):');
        $this->info('─────────────────────────────────────────────────────────────────────');
        if (count($usersByPlan['basico']) > 0) {
            foreach ($usersByPlan['basico'] as $user) {
                $remaining = $user->activeSubscription->getRemainingWeeklyMessages();
                $this->line('  • ' . $user->name . ' (ID: ' . $user->id . ') - Mensajes restantes: ' . $remaining . '/3');
            }
        } else {
            $this->line('  (ninguno)');
        }
        $this->info('');

        // Plan Premium
        $this->info('💎 USUARIOS CON PLAN PREMIUM (' . count($usersByPlan['premium']) . '):');
        $this->info('─────────────────────────────────────────────────────────────────────');
        if (count($usersByPlan['premium']) > 0) {
            foreach ($usersByPlan['premium'] as $user) {
                $this->line('  • ' . $user->name . ' (ID: ' . $user->id . ') - Mensajes ilimitados');
            }
        } else {
            $this->line('  (ninguno)');
        }
        $this->info('');
    }

    private function createPermissionMatrix($usersByPlan)
    {
        $allUsers = array_merge(
            $usersByPlan['free'],
            $usersByPlan['basico'],
            $usersByPlan['premium']
        );

        if (count($allUsers) < 2) {
            $this->warn('Se necesitan al menos 2 usuarios para crear la matriz.');
            return;
        }

        // Headers
        $this->line('  Remitente → Receptor │ Puede Enviar │ Límite');
        $this->line('  ────────────────────────────────────────────────────────────────');

        foreach ($allUsers as $sender) {
            $senderPlan = $sender->activeSubscription ? $sender->activeSubscription->plan->slug : 'free';
            $senderIcon = $this->getPlanIcon($senderPlan);

            foreach ($allUsers as $receiver) {
                if ($sender->id === $receiver->id) continue;

                $receiverPlan = $receiver->activeSubscription ? $receiver->activeSubscription->plan->slug : 'free';
                $receiverIcon = $this->getPlanIcon($receiverPlan);

                // Determinar si puede enviar
                $canSend = $this->canUserSendTo($sender, $receiver);
                $limit = $this->getLimit($sender, $receiver);

                $status = $canSend ? '<fg=green>✓ Sí</>' : '<fg=red>✗ No</>';

                $this->line(sprintf(
                    '  %s %s → %s %s │ %s │ %s',
                    $senderIcon,
                    str_pad($sender->name, 8),
                    $receiverIcon,
                    str_pad($receiver->name, 8),
                    $status,
                    $limit
                ));
            }
        }
    }

    private function canUserSendTo($sender, $receiver)
    {
        $senderSubscription = $sender->activeSubscription;

        // Usuario gratis sin suscripción
        if (!$senderSubscription) {
            return false; // Solo puede responder
        }

        return $senderSubscription->canSendMessageTo($receiver);
    }

    private function getLimit($sender, $receiver)
    {
        $senderSubscription = $sender->activeSubscription;
        $receiverSubscription = $receiver->activeSubscription;

        // Usuario gratis
        if (!$senderSubscription) {
            return 'Solo responder';
        }

        $senderPlan = $senderSubscription->plan;
        $receiverPlan = $receiverSubscription ? $receiverSubscription->plan : null;

        // Premium
        if ($senderPlan->mensajes_ilimitados) {
            return 'Ilimitado';
        }

        // Básico enviando a Gratis
        if ($senderPlan->slug === 'basico' && (!$receiverPlan || $receiverPlan->slug === 'free')) {
            $remaining = $senderSubscription->getRemainingWeeklyMessages();
            return $remaining . '/3 semana';
        }

        // Básico enviando a Básico/Premium
        if ($senderPlan->slug === 'basico') {
            return 'Ilimitado';
        }

        return 'N/A';
    }

    private function getPlanIcon($planSlug)
    {
        return match($planSlug) {
            'free' => '👤',
            'basico' => '⭐',
            'premium' => '💎',
            default => '❓',
        };
    }
}
