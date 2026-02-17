<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use App\Services\PayPalService;

class SyncPayPalPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:sync-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar planes de suscripción con PayPal';

    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        parent::__construct();
        $this->paypalService = $paypalService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando sincronización de planes con PayPal...');
        $this->newLine();

        try {
            // Obtener todos los planes activos
            $plans = Plan::where('activo', true)->get();

            if ($plans->isEmpty()) {
                $this->error('❌ No se encontraron planes activos en la base de datos.');
                return 1;
            }

            foreach ($plans as $plan) {
                $this->info("📦 Procesando plan: {$plan->nombre}");

                // Validar que el plan no tenga ambos precios (cada plan debe ser mensual O anual)
                if ($plan->precio_mensual > 0 && $plan->precio_anual > 0) {
                    $this->warn("   ⚠ ADVERTENCIA: El plan '{$plan->nombre}' tiene precio mensual ({$plan->precio_mensual}) Y anual ({$plan->precio_anual}).");
                    $this->warn("     Cada plan debe tener solo UN tipo de precio. Se omite este plan.");
                    $this->newLine();
                    continue;
                }

                // Crear producto en PayPal (uno por plan)
                $productName = "Citas Mallorca - {$plan->nombre}";

                // Generar descripción según el nombre del plan
                $productDescription = match(strtolower($plan->slug)) {
                    'free', 'gratis' => 'Plan gratuito con funciones básicas para conocer gente',
                    'mensual' => 'Suscripción mensual premium con mensajes ilimitados y ver quien te ha dado like',
                    'anual' => 'Suscripción anual premium con todas las funciones y el mejor precio',
                    default => "Suscripción al plan {$plan->nombre} de Citas Mallorca"
                };

                $this->line("   → Creando producto en PayPal...");

                try {
                    $product = $this->paypalService->createProduct($productName, $productDescription);
                    $productId = $product['id'];
                    $this->info("   ✓ Producto creado: {$productId}");
                } catch (\Exception $e) {
                    $this->error("   ✗ Error creando producto: {$e->getMessage()}");
                    continue;
                }

                // Crear plan mensual si tiene precio mensual
                if ($plan->precio_mensual && $plan->precio_mensual > 0) {
                    $this->line("   → Creando plan mensual en PayPal...");

                    try {
                        $billingPlan = $this->paypalService->createBillingPlan(
                            $productId,
                            "{$plan->nombre} - Mensual",
                            "Suscripción mensual a {$plan->nombre}",
                            $plan->precio_mensual,
                            'MONTH'
                        );

                        $plan->paypal_plan_id_mensual = $billingPlan['id'];
                        $this->info("   ✓ Plan mensual creado: {$billingPlan['id']}");
                    } catch (\Exception $e) {
                        $this->error("   ✗ Error creando plan mensual: {$e->getMessage()}");
                    }
                }

                // Crear plan anual si tiene precio anual
                if ($plan->precio_anual && $plan->precio_anual > 0) {
                    $this->line("   → Creando plan anual en PayPal...");

                    try {
                        $billingPlan = $this->paypalService->createBillingPlan(
                            $productId,
                            "{$plan->nombre} - Anual",
                            "Suscripción anual a {$plan->nombre}",
                            $plan->precio_anual,
                            'YEAR'
                        );

                        $plan->paypal_plan_id_anual = $billingPlan['id'];
                        $this->info("   ✓ Plan anual creado: {$billingPlan['id']}");
                    } catch (\Exception $e) {
                        $this->error("   ✗ Error creando plan anual: {$e->getMessage()}");
                    }
                }

                // Guardar los IDs de PayPal y la descripción en la base de datos
                $plan->descripcion = $productDescription;
                $plan->save();
                $this->newLine();
            }

            $this->newLine();
            $this->info('✅ Sincronización completada exitosamente!');
            $this->newLine();

            // Mostrar resumen
            $this->table(
                ['Plan', 'ID PayPal Mensual', 'ID PayPal Anual'],
                Plan::where('activo', true)->get()->map(function ($plan) {
                    return [
                        $plan->nombre,
                        $plan->paypal_plan_id_mensual ?? '—',
                        $plan->paypal_plan_id_anual ?? '—',
                    ];
                })
            );

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error general: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
