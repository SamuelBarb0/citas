<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Citas Mallorca</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .steps {
            background-color: #f9f9f9;
            border-left: 4px solid #8B4513;
            padding: 20px;
            margin: 30px 0;
        }
        .steps h3 {
            margin-top: 0;
            color: #8B4513;
            font-size: 18px;
        }
        .steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .steps li {
            margin: 10px 0;
            color: #555;
        }
        .cta {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 30px;
            text-align: center;
            color: #888;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌴 Citas Mallorca</h1>
            <p>Tu cuenta ha sido creada exitosamente</p>
        </div>

        <div class="content">
            <p class="greeting">¡Hola {{ $user->name }}!</p>

            <p class="message">
                Bienvenido a <strong>Citas Mallorca</strong>, la plataforma donde encontrarás conexiones auténticas en la isla.
            </p>

            <p class="message">
                Tu cuenta ha sido creada correctamente con el email: <strong>{{ $user->email }}</strong>
            </p>

            <div class="steps">
                <h3>Próximos pasos:</h3>
                <ol>
                    <li><strong>Completa tu perfil</strong> - Añade fotos y describe quién eres</li>
                    <li><strong>Verifica tu identidad</strong> - Sube una foto para confirmar que eres una persona real</li>
                    <li><strong>Espera la aprobación</strong> - Nuestro equipo revisará tu verificación en 24-48 horas</li>
                    <li><strong>Empieza a conocer gente</strong> - Una vez verificado, podrás usar todas las funciones</li>
                </ol>
            </div>

            <p class="message">
                Recuerda que la verificación de identidad es <strong>obligatoria</strong> para poder usar la aplicación.
                Esto nos ayuda a mantener la comunidad segura y libre de cuentas falsas.
            </p>

            <div class="cta">
                <a href="{{ url('/login') }}" class="button">Ir a Citas Mallorca</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Citas Mallorca</strong></p>
            <p>Conexiones auténticas en la isla</p>
            <p style="margin-top: 15px; font-size: 12px; color: #aaa;">
                Este es un correo automático, por favor no respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
