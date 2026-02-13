<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "=== PROBANDO ENVÍO DE EMAIL ===\n\n";

try {
    Mail::raw('Prueba desde Citas Mallorca - Sistema de emails funcionando correctamente', function ($message) {
        $message->to('info@citasmallorca.es')
                ->subject('Test Email - Citas Mallorca');
    });

    echo "✅ Email enviado exitosamente a info@citasmallorca.es\n";
    echo "📧 Revisa tu bandeja de entrada\n";

} catch (\Exception $e) {
    echo "❌ Error al enviar email:\n";
    echo $e->getMessage() . "\n\n";
    echo "Traza:\n" . $e->getTraceAsString() . "\n";
}
