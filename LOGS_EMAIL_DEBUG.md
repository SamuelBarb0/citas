# 🔍 LOGS DETALLADOS PARA DEBUGGING DE EMAILS

## ✅ Cambios Realizados

He agregado logs super detallados en **TODOS** los puntos donde se intenta enviar el email de suscripción activada.

---

## 📍 UBICACIONES CON LOGS

### 1. **SubscriptionController::activatePayPalSubscription()**
**Archivo:** `app/Http/Controllers/SubscriptionController.php` (líneas 472-520)

**Logs agregados:**
- ✅ Inicio del proceso de envío
- ✅ Configuración de correo detectada (host, username, from)
- ✅ Resultado de verificación de configuración
- ✅ Intento de envío
- ✅ Éxito o error detallado

### 2. **PayPalWebhookController::createSubscriptionFromWebhook()**
**Archivo:** `app/Http/Controllers/PayPalWebhookController.php` (líneas 273-315)

**Logs agregados:**
- ✅ Inicio del proceso de envío
- ✅ Configuración de correo
- ✅ Intento de envío
- ✅ Éxito o error detallado

---

## 🧪 CÓMO USAR LOS LOGS

### Opción 1: Ver logs en tiempo real (RECOMENDADO)

Abre una terminal y ejecuta:

```bash
# Ver TODOS los logs relacionados con email
tail -f storage/logs/laravel.log | grep -i "email\|mail\|PAYPAL ACTIVAR"

# O más específico - solo los del proceso de activación
tail -f storage/logs/laravel.log | grep "PAYPAL ACTIVAR"
```

**Deja esta terminal abierta mientras haces la prueba de suscripción.**

### Opción 2: Ver logs después de la prueba

```bash
# Ver las últimas 200 líneas relacionadas con PayPal
tail -200 storage/logs/laravel.log | grep "PAYPAL ACTIVAR"

# Ver las últimas 200 líneas relacionadas con email
tail -200 storage/logs/laravel.log | grep -i "email"
```

---

## 📊 QUÉ VERÁS EN LOS LOGS

### ✅ Caso EXITOSO (email enviado):

```
[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: Iniciando proceso de envío de email {"user_id":1,"user_email":"test@ejemplo.com","plan":"Premium"}

[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: Configuración de correo detectada {"mail_host":"mail.citasmallorca.es","mail_username":"info@citasmallorca.es","mail_from":"info@citasmallorca.es","mail_mailer":"smtp"}

[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: Resultado verificación de configuración {"mail_configured":true,"host_check":true,"username_check":true,"username_not_default":true}

[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: Intentando enviar email...

[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: ✅ Email de bienvenida enviado exitosamente {"user_email":"test@ejemplo.com","plan":"Premium","to":"test@ejemplo.com","from":"info@citasmallorca.es"}
```

### ⚠️ Caso ADVERTENCIA (configuración no válida):

```
[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: Configuración de correo detectada {"mail_host":"smtp.mailgun.org","mail_username":null,"mail_from":"hello@example.com","mail_mailer":"smtp"}

[2026-02-12 XX:XX:XX] local.WARNING: PAYPAL ACTIVAR: ⚠️ Email NO enviado - configuración de correo no válida {"user_email":"test@ejemplo.com","mail_host":"smtp.mailgun.org","mail_username":null,"reason":"Configuración de correo no cumple con los requisitos"}
```

### ❌ Caso ERROR (fallo al enviar):

```
[2026-02-12 XX:XX:XX] local.INFO: PAYPAL ACTIVAR: Intentando enviar email...

[2026-02-12 XX:XX:XX] local.ERROR: PAYPAL ACTIVAR: ❌ Error enviando email de bienvenida (no bloquea activación) {"error":"Connection could not be established with host mail.citasmallorca.es","error_class":"Swift_TransportException","error_file":"vendor/...","error_line":123,"user_id":1,"user_email":"test@ejemplo.com","trace":"..."}
```

---

## 🎯 PASO A PASO PARA DEBUGGING

### 1. Preparar monitoreo de logs

**Terminal 1** (monitoreo en tiempo real):
```bash
cd C:\xampp\htdocs\citas
tail -f storage/logs/laravel.log | grep "PAYPAL ACTIVAR"
```

**Terminal 2** (comandos normales):
```bash
cd C:\xampp\htdocs\citas
```

### 2. Verificar configuración actual

```bash
php -r "echo 'Mail Host: ' . config('mail.mailers.smtp.host') . PHP_EOL;"
php -r "echo 'Mail User: ' . config('mail.mailers.smtp.username') . PHP_EOL;"
php -r "echo 'Mail From: ' . config('mail.from.address') . PHP_EOL;"
```

Deberías ver:
```
Mail Host: mail.citasmallorca.es
Mail User: info@citasmallorca.es
Mail From: info@citasmallorca.es
```

### 3. Hacer prueba de suscripción

1. Asegúrate de que MySQL esté corriendo en XAMPP
2. Ve a tu app: https://tu-dominio.com/subscriptions
3. Selecciona un plan (ej: Básico Mensual)
4. Completa el pago en PayPal Sandbox/Producción
5. **OBSERVA LA TERMINAL 1** mientras se procesa

### 4. Analizar resultados

**Si ves: `✅ Email de bienvenida enviado exitosamente`**
- ¡Funciona! Revisa tu bandeja de entrada en `info@citasmallorca.es`
- Si no llega, revisa SPAM o verifica la configuración del servidor de correo

**Si ves: `⚠️ Email NO enviado - configuración de correo no válida`**
- La configuración en `.env` no cumple los requisitos
- Ejecuta el paso 2 para verificar
- Asegúrate de haber ejecutado `php artisan config:clear`

**Si ves: `❌ Error enviando email`**
- Hay un problema al conectar con el servidor SMTP
- Revisa el mensaje de error completo
- Posibles causas:
  - Contraseña incorrecta
  - Puerto bloqueado por firewall
  - Servidor SMTP caído
  - SSL/TLS mal configurado

---

## 🔧 SOLUCIONES RÁPIDAS

### Error: "Connection could not be established"

**Causa:** No puede conectar al servidor SMTP

**Solución:**
```bash
# Probar conectividad
telnet mail.citasmallorca.es 465

# Si no responde, verifica:
# 1. ¿El servidor está activo?
# 2. ¿El puerto 465 está abierto?
# 3. ¿Firewall bloqueando?
```

### Error: "Username and Password not accepted"

**Causa:** Credenciales incorrectas

**Solución:**
```env
# Verifica en .env:
MAIL_USERNAME=info@citasmallorca.es
MAIL_PASSWORD="Carol-369"

# Asegúrate de que las comillas estén si la contraseña tiene caracteres especiales
```

### Warning: "Email NO enviado - configuración no válida"

**Causa:** La configuración no pasa las validaciones

**Solución:**
```bash
# Limpiar caché
php artisan config:clear

# Verificar que .env tenga:
MAIL_MAILER=smtp
MAIL_HOST=mail.citasmallorca.es
MAIL_USERNAME=info@citasmallorca.es
```

---

## 📧 PROBAR EMAIL MANUALMENTE

Si quieres probar el envío sin hacer una suscripción completa:

```bash
php test-subscription-email.php
```

Esto:
1. Busca un usuario en la BD
2. Crea una suscripción temporal (no se guarda)
3. Envía el email
4. Muestra logs detallados

---

## 📝 EJEMPLO DE SESIÓN DE DEBUG

```bash
# Terminal 1 - Monitoreo
$ tail -f storage/logs/laravel.log | grep "PAYPAL ACTIVAR"

# Terminal 2 - Hacer prueba
$ php test-subscription-email.php

# Lo que verás en Terminal 1:
[2026-02-12 15:30:45] local.INFO: PAYPAL ACTIVAR: Iniciando proceso de envío de email ...
[2026-02-12 15:30:45] local.INFO: PAYPAL ACTIVAR: Configuración de correo detectada ...
[2026-02-12 15:30:45] local.INFO: PAYPAL ACTIVAR: Resultado verificación de configuración {"mail_configured":true,...}
[2026-02-12 15:30:45] local.INFO: PAYPAL ACTIVAR: Intentando enviar email...
[2026-02-12 15:30:47] local.INFO: PAYPAL ACTIVAR: ✅ Email de bienvenida enviado exitosamente ...
```

---

## ✅ CHECKLIST

Antes de hacer la prueba:

- [ ] MySQL está corriendo en XAMPP
- [ ] `.env` tiene configuración de email correcta
- [ ] Ejecuté `php artisan config:clear`
- [ ] Tengo una terminal con `tail -f` corriendo
- [ ] Tengo acceso a la bandeja `info@citasmallorca.es`

Durante la prueba:

- [ ] Veo los logs aparecer en tiempo real
- [ ] Los logs muestran la configuración correcta
- [ ] Veo `✅ Email enviado exitosamente` o `❌ Error...`
- [ ] Si hay error, veo el mensaje completo

Después de la prueba:

- [ ] Reviso la bandeja de entrada
- [ ] Reviso carpeta SPAM
- [ ] Si no llegó, reviso los logs guardados
- [ ] Documento el problema encontrado

---

**¡Ahora estás listo para hacer la prueba con logs completos!**

Cualquier error que aparezca, copia el mensaje completo y podré ayudarte a solucionarlo.
