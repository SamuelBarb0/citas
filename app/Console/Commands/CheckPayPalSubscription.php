<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayPalService;

class CheckPayPalSubscription extends Command
{
    protected $signature = 'paypal:check-subscription {subscription_id}';
    protected $description = 'Verificar el estado de una suscripción en PayPal';

    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        parent::__construct();
        $this->paypalService = $paypalService;
    }

    public function handle()
    {
        $subscriptionId = $this->argument('subscription_id');

        $this->info("Verificando suscripción: {$subscriptionId}");
        $this->newLine();

        // Obtener detalles de la suscripción
        $this->line('→ Obteniendo detalles de la suscripción...');
        $subscription = $this->paypalService->getSubscription($subscriptionId);

        if (!$subscription) {
            $this->error('No se pudo obtener la suscripción de PayPal');
            return 1;
        }

        $this->info('Detalles de la suscripción:');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID', $subscription['id'] ?? 'N/A'],
                ['Estado', $subscription['status'] ?? 'N/A'],
                ['Plan ID', $subscription['plan_id'] ?? 'N/A'],
                ['Fecha inicio', $subscription['start_time'] ?? 'N/A'],
                ['Próximo cobro', $subscription['billing_info']['next_billing_time'] ?? 'N/A'],
                ['Último pago', $subscription['billing_info']['last_payment']['time'] ?? 'N/A'],
                ['Monto último pago', isset($subscription['billing_info']['last_payment']['amount'])
                    ? $subscription['billing_info']['last_payment']['amount']['value'] . ' ' . $subscription['billing_info']['last_payment']['amount']['currency_code']
                    : 'N/A'],
            ]
        );

        $this->newLine();

        // Obtener transacciones
        $this->line('→ Obteniendo transacciones...');
        $transactions = $this->paypalService->getSubscriptionTransactions($subscriptionId);

        if ($transactions && isset($transactions['transactions']) && count($transactions['transactions']) > 0) {
            $this->info('Transacciones encontradas: ' . count($transactions['transactions']));

            $rows = [];
            foreach ($transactions['transactions'] as $tx) {
                $rows[] = [
                    $tx['id'] ?? 'N/A',
                    $tx['status'] ?? 'N/A',
                    isset($tx['amount_with_breakdown']['gross_amount'])
                        ? $tx['amount_with_breakdown']['gross_amount']['value'] . ' ' . $tx['amount_with_breakdown']['gross_amount']['currency_code']
                        : 'N/A',
                    $tx['time'] ?? 'N/A',
                ];
            }

            $this->table(['ID Transacción', 'Estado', 'Monto', 'Fecha'], $rows);
        } else {
            $this->warn('No se encontraron transacciones');
        }

        $this->newLine();

        // Información del suscriptor
        if (isset($subscription['subscriber'])) {
            $this->info('Información del suscriptor:');
            $this->line('  Email: ' . ($subscription['subscriber']['email_address'] ?? 'N/A'));
            if (isset($subscription['subscriber']['name'])) {
                $this->line('  Nombre: ' . ($subscription['subscriber']['name']['given_name'] ?? '') . ' ' . ($subscription['subscriber']['name']['surname'] ?? ''));
            }
        }

        return 0;
    }
}
