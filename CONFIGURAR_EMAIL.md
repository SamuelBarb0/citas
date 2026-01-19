# Configuración de Email para Citas Mallorca

Para que las notificaciones por email funcionen correctamente, necesitas configurar Gmail con una contraseña de aplicación.

## Pasos para configurar Gmail:

### 1. Habilitar verificación en dos pasos
1. Ve a tu cuenta de Google: https://myaccount.google.com/
2. Selecciona "Seguridad" en el menú lateral
3. En "Acceso a Google", habilita la "Verificación en dos pasos"
4. Sigue los pasos para configurarla

### 2. Crear una contraseña de aplicación
1. Una vez habilitada la verificación en dos pasos, vuelve a "Seguridad"
2. En "Acceso a Google", busca "Contraseñas de aplicaciones"
3. Haz clic en "Contraseñas de aplicaciones"
4. Selecciona "Correo" y "Otro (nombre personalizado)"
5. Escribe "Citas Mallorca" como nombre
6. Haz clic en "Generar"
7. Google te mostrará una contraseña de 16 caracteres (ejemplo: abcd efgh ijkl mnop)

### 3. Actualizar el archivo .env
Abre el archivo `.env` y actualiza estas líneas:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com          # ← Cambia esto por tu email real
MAIL_PASSWORD=abcdefghijklmnop            # ← Pega la contraseña de aplicación (sin espacios)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@citasmallorca.es"
MAIL_FROM_NAME="${APP_NAME}"
```

**IMPORTANTE:**
- La contraseña debe estar SIN espacios
- NO uses tu contraseña normal de Gmail, usa la contraseña de aplicación generada
- Asegúrate de que `MAIL_USERNAME` sea tu email completo de Gmail

### 4. Verificar la configuración
Después de actualizar el .env, puedes probar enviando un email de prueba:

```bash
php artisan tinker
```

Luego ejecuta:
```php
Mail::raw('Email de prueba desde Citas Mallorca', function ($message) {
    $message->to('tu-email@gmail.com')
            ->subject('Prueba de Email');
});
```

Si recibes el email, ¡está funcionando correctamente!

## Alternativa: Usar Mailtrap (para desarrollo)

Si solo estás desarrollando y no necesitas enviar emails reales, puedes usar Mailtrap:

1. Crea una cuenta gratuita en https://mailtrap.io/
2. Ve a "Email Testing" → "Inboxes" → "My Inbox"
3. Selecciona "Laravel 7+" en las integraciones
4. Copia las credenciales a tu .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username-de-mailtrap
MAIL_PASSWORD=tu-password-de-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@citasmallorca.es"
MAIL_FROM_NAME="${APP_NAME}"
```

Con Mailtrap, todos los emails se capturan en tu inbox de prueba y no se envían realmente.

## Estado Actual

Por ahora, el sistema está configurado para NO fallar si el email no está configurado. Las suscripciones se activarán correctamente aunque no se envíen los emails de confirmación.

Cuando configures correctamente el email, los usuarios recibirán:
- ✉️ Email de bienvenida al activar suscripción
- ✉️ Email de confirmación al renovar suscripción
- ✉️ Email de aviso si falla un pago
