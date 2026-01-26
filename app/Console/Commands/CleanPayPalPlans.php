<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use App\Services\PayPalService;

class CleanPayPalPlans extends Command
{
    protected $signature = 'paypal:clean-plans';
    protected $description = 'Desactivar todos los planes en PayPal y limpiar IDs locales';

    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        parent::__construct();
        $this->paypalService = $paypalService;
    }

    public function handle()
    {
        $this->info('🧹 Limpiando planes de PayPal...');
        $this->newLine();

        // 1. Obtener todos los planes de PayPal
        $this->line('→ Obteniendo lista de planes de PayPal...');
        $paypalPlans = $this->paypalService->listPlans(100);

        if ($paypalPlans && isset($paypalPlans['plans'])) {
            $plans = $paypalPlans['plans'];
            $this->info("   Encontrados " . count($plans) . " planes en PayPal");

            foreach ($plans as $plan) {
                if ($plan['status'] === 'ACTIVE') {
                    $this->line("   → Desactivando plan: {$plan['id']} ({$plan['name']})");
                    $result = $this->paypalService->deactivatePlan($plan['id']);
                    if ($result) {
                        $this->info("   ✓ Plan desactivado");
                    } else {
                        $this->error("   ✗ Error al desactivar");
                    }
                } else {
                    $this->line("   → Plan ya inactivo: {$plan['id']} ({$plan['name']})");
                }
            }
        } else {
            $this->warn('   No se encontraron planes o error al obtenerlos');
        }

        $this->newLine();

        // 2. Limpiar IDs en la base de datos local
        $this->line('→ Limpiando IDs de PayPal en base de datos local...');
        Plan::query()->update([
            'paypal_plan_id_mensual' => null,
            'paypal_plan_id_anual' => null
        ]);
        $this->info('   ✓ IDs de PayPal limpiados');

        $this->newLine();
        $this->info('✅ Limpieza completada!');
        $this->newLine();
        $this->line('Ahora ejecuta: php artisan paypal:sync-plans');

        return 0;
    }
}
