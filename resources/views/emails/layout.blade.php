<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Citas Mallorca</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #FDF8F3; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #FDF8F3;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <!-- Container -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom: 30px;">
                            <img src="{{ config('app.url') }}/images/LOGOCITAS.png" alt="Citas Mallorca" width="80" height="80" style="display: block;">
                            <h1 style="margin: 15px 0 0 0; color: #5D4E37; font-size: 24px; font-weight: bold;">Citas Mallorca</h1>
                        </td>
                    </tr>

                    <!-- Main Content Card -->
                    <tr>
                        <td>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #FFFFFF; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">

                                <!-- Icon Header -->
                                <tr>
                                    <td align="center" style="padding: 40px 40px 20px 40px;">
                                        @yield('icon')
                                    </td>
                                </tr>

                                <!-- Title -->
                                <tr>
                                    <td align="center" style="padding: 0 40px 10px 40px;">
                                        <h2 style="margin: 0; color: #5D4E37; font-size: 28px; font-weight: bold;">
                                            @yield('heading')
                                        </h2>
                                    </td>
                                </tr>

                                <!-- Subtitle -->
                                <tr>
                                    <td align="center" style="padding: 0 40px 30px 40px;">
                                        <p style="margin: 0; color: #666666; font-size: 16px; line-height: 1.5;">
                                            @yield('subheading')
                                        </p>
                                    </td>
                                </tr>

                                <!-- Content -->
                                <tr>
                                    <td style="padding: 0 40px 30px 40px;">
                                        @yield('content')
                                    </td>
                                </tr>

                                <!-- CTA Button -->
                                @hasSection('cta')
                                <tr>
                                    <td align="center" style="padding: 0 40px 40px 40px;">
                                        @yield('cta')
                                    </td>
                                </tr>
                                @endif

                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 30px 20px;">
                            <p style="margin: 0 0 10px 0; color: #999999; font-size: 14px;">
                                Encuentra el amor en la isla
                            </p>
                            <p style="margin: 0 0 15px 0; color: #BBBBBB; font-size: 12px;">
                                &copy; {{ date('Y') }} Citas Mallorca. Todos los derechos reservados.
                            </p>
                            <p style="margin: 0; font-size: 12px;">
                                <a href="{{ config('app.url') }}/politica-privacidad" style="color: #999999; text-decoration: none;">Privacidad</a>
                                &nbsp;&middot;&nbsp;
                                <a href="{{ config('app.url') }}/terminos-condiciones" style="color: #999999; text-decoration: none;">Términos</a>
                                &nbsp;&middot;&nbsp;
                                <a href="{{ config('app.url') }}/mi-suscripcion" style="color: #999999; text-decoration: none;">Mi Suscripción</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
