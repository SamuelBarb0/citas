<?php

namespace App\Console\Commands;

use App\Services\PayPalService;
use Illuminate\Console\Command;

class ListPayPalTransactions extends Command
{
    protected $signature = 'paypal:transactions
                            {--days=30 : Número de días hacia atrás para buscar}
                            {--start= : Fecha de inicio (YYYY-MM-DD)}
                            {--end= : Fecha de fin (YYYY-MM-DD)}';

    protected $description = 'Lista todas las transacciones de PayPal';

    public function handle()
    {
        $this->info('🔍 Consultando transacciones de PayPal...');
        $this->newLine();

        $paypalService = app(PayPalService::class);

        // Determinar rango de fechas
        if ($this->option('start') && $this->option('end')) {
            $startDate = $this->option('start') . 'T00:00:00Z';
            $endDate = $this->option('end') . 'T23:59:59Z';
        } else {
            $days = (int) $this->option('days');
            $endDate = now()->toIso8601String();
            $startDate = now()->subDays($days)->toIso8601String();
        }

        $this->info("📅 Período: " . substr($startDate, 0, 10) . " a " . substr($endDate, 0, 10));
        $this->newLine();

        try {
            $transactions = $paypalService->listTransactions($startDate, $endDate);

            if (empty($transactions)) {
                $this->warn('No se encontraron transacciones en este período.');
                return 0;
            }

            $this->info('💰 Transacciones encontradas: ' . count($transactions));
            $this->newLine();

            $tableData = [];
            $totalAmount = 0;

            foreach ($transactions as $transaction) {
                $info = $transaction['transaction_info'] ?? [];
                $payer = $transaction['payer_info'] ?? [];

                $amount = $info['transaction_amount']['value'] ?? '0.00';
                $currency = $info['transaction_amount']['currency_code'] ?? 'EUR';
                $status = $info['transaction_status'] ?? 'N/A';
                $date = isset($info['transaction_initiation_date'])
                    ? date('Y-m-d H:i', strtotime($info['transaction_initiation_date']))
                    : 'N/A';

                // Solo sumar si es un pago completado (no reembolso)
                if ($status === 'S' && floatval($amount) > 0) {
                    $totalAmount += floatval($amount);
                }

                $tableData[] = [
                    'ID' => substr($info['transaction_id'] ?? 'N/A', 0, 20),
                    'Fecha' => $date,
                    'Email' => $payer['email_address'] ?? 'N/A',
                    'Nombre' => ($payer['payer_name']['given_name'] ?? '') . ' ' . ($payer['payer_name']['surname'] ?? ''),
                    'Monto' => $amount . ' ' . $currency,
                    'Estado' => $this->getStatusLabel($status),
                    'Tipo' => $info['transaction_event_code'] ?? 'N/A',
                ];
            }

            $this->table(
                ['ID', 'Fecha', 'Email', 'Nombre', 'Monto', 'Estado', 'Tipo'],
                $tableData
            );

            $this->newLine();
            $this->info("💵 Total recaudado (pagos completados): {$totalAmount} EUR");

        } catch (\Exception $e) {
            $this->error('Error al consultar PayPal: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'S' => '✅ Completado',
            'P' => '⏳ Pendiente',
            'V' => '↩️ Revertido',
            'D' => '❌ Rechazado',
            default => $status,
        };
    }
}
