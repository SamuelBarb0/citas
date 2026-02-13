<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "=== DIAGNÓSTICO DE SERVIDOR SMTP ===\n\n";

// 1. Mostrar configuración actual
echo "📋 CONFIGURACIÓN ACTUAL:\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "   MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***configurada***' : 'NO CONFIGURADA') . "\n";
echo "   MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "   MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";

// 2. Probar conexión al servidor SMTP
echo "🔌 PROBANDO CONEXIÓN AL SERVIDOR SMTP...\n";
$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');

$connection = @fsockopen($host, $port, $errno, $errstr, 10);
if ($connection) {
    echo "   ✅ Conexión exitosa a {$host}:{$port}\n";
    fclose($connection);
} else {
    echo "   ❌ No se pudo conectar a {$host}:{$port}\n";
    echo "   Error: {$errstr} (código: {$errno})\n\n";
    echo "   Posibles causas:\n";
    echo "   - El servidor SMTP está caído\n";
    echo "   - El puerto {$port} está bloqueado por firewall\n";
    echo "   - La dirección del host es incorrecta\n\n";
    exit(1);
}

// 3. Intentar enviar email de prueba
echo "\n📧 ENVIANDO EMAIL DE PRUEBA...\n";
echo "   Destinatario: " . config('mail.mailers.smtp.username') . "\n\n";

try {
    Mail::raw('Este es un email de prueba desde Citas Mallorca.

Si estás leyendo esto, significa que el sistema de correo está funcionando correctamente.

Detalles técnicos:
- Servidor: ' . config('mail.mailers.smtp.host') . '
- Puerto: ' . config('mail.mailers.smtp.port') . '
- Encriptación: ' . config('mail.mailers.smtp.encryption') . '
- Hora de envío: ' . now()->format('Y-m-d H:i:s') . '

Saludos,
Sistema Citas Mallorca', function ($message) {
        $to = config('mail.mailers.smtp.username');
        $message->to($to)
                ->subject('🧪 Prueba de Email - Citas Mallorca');
    });

    echo "   ✅ Email enviado exitosamente\n\n";
    echo "📬 SIGUIENTE PASO:\n";
    echo "   1. Revisa la bandeja de entrada: " . config('mail.mailers.smtp.username') . "\n";
    echo "   2. Revisa la carpeta de SPAM\n";
    echo "   3. Revisa los logs del servidor de correo\n\n";

    echo "⏱️  Espera 1-2 minutos para que llegue el email\n\n";

} catch (\Exception $e) {
    echo "   ❌ Error al enviar email:\n\n";
    echo "   Mensaje: " . $e->getMessage() . "\n";
    echo "   Clase: " . get_class($e) . "\n";
    echo "   Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n\n";

    // Análisis del error
    $errorMsg = $e->getMessage();

    if (stripos($errorMsg, 'authentication') !== false || stripos($errorMsg, 'username') !== false || stripos($errorMsg, 'password') !== false) {
        echo "   🔍 DIAGNÓSTICO:\n";
        echo "   Este es un error de autenticación.\n\n";
        echo "   SOLUCIONES:\n";
        echo "   1. Verifica que el usuario y contraseña sean correctos\n";
        echo "   2. Verifica que la cuenta de email exista en el servidor\n";
        echo "   3. Verifica que la contraseña no haya expirado\n\n";
    } elseif (stripos($errorMsg, 'connection') !== false || stripos($errorMsg, 'timed out') !== false) {
        echo "   🔍 DIAGNÓSTICO:\n";
        echo "   Este es un error de conexión.\n\n";
        echo "   SOLUCIONES:\n";
        echo "   1. Verifica que el servidor SMTP esté funcionando\n";
        echo "   2. Verifica el firewall no esté bloqueando el puerto " . config('mail.mailers.smtp.port') . "\n";
        echo "   3. Intenta con otro puerto (25, 587, 465)\n\n";
    } elseif (stripos($errorMsg, 'certificate') !== false || stripos($errorMsg, 'ssl') !== false || stripos($errorMsg, 'tls') !== false) {
        echo "   🔍 DIAGNÓSTICO:\n";
        echo "   Este es un error de certificado SSL/TLS.\n\n";
        echo "   SOLUCIONES:\n";
        echo "   1. Verifica que MAIL_ENCRYPTION sea 'ssl' o 'tls' según corresponda\n";
        echo "   2. Para puerto 465 usa 'ssl'\n";
        echo "   3. Para puerto 587 usa 'tls'\n";
        echo "   4. Puedes probar temporalmente sin encriptación (no recomendado en producción)\n\n";
    }

    echo "   Traza completa:\n";
    echo "   " . str_replace("\n", "\n   ", $e->getTraceAsString()) . "\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
