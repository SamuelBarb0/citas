@extends('emails.layout')

@section('title', 'Suscripción Activada')

@section('icon')
<div style="width: 80px; height: 80px; background: linear-gradient(135deg, #E94057 0%, #F27121 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
    <table role="presentation" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 80px; height: 80px; background: linear-gradient(135deg, #E94057 0%, #F27121 100%); border-radius: 50%; text-align: center; vertical-align: middle;">
                <span style="font-size: 36px;">&#10003;</span>
            </td>
        </tr>
    </table>
</div>
@endsection

@section('heading')
    ¡Bienvenido/a, {{ $user->name }}!
@endsection

@section('subheading')
    Tu suscripción se ha activado correctamente
@endsection

@section('content')
<!-- Plan Info Card -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: linear-gradient(135deg, #E94057 0%, #F27121 100%); border-radius: 16px; margin-bottom: 25px;">
    <tr>
        <td style="padding: 25px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <p style="margin: 0 0 5px 0; color: rgba(255,255,255,0.8); font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Tu Plan</p>
                        <h3 style="margin: 0; color: #FFFFFF; font-size: 24px; font-weight: bold;">{{ $plan->nombre }}</h3>
                    </td>
                    <td align="right">
                        <p style="margin: 0; color: #FFFFFF; font-size: 32px; font-weight: bold;">{{ number_format($subscription->monto_pagado, 2) }}€</p>
                        <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.8); font-size: 14px;">{{ ucfirst($subscription->tipo) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Details -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #F8F4F0; border-radius: 12px;">
    <tr>
        <td style="padding: 20px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #E8E0D8;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Fecha de inicio</td>
                                <td align="right" style="color: #5D4E37; font-size: 14px; font-weight: 600;">{{ $subscription->fecha_inicio->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #E8E0D8;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Próxima renovación</td>
                                <td align="right" style="color: #5D4E37; font-size: 14px; font-weight: 600;">{{ $subscription->fecha_expiracion->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Método de pago</td>
                                <td align="right" style="color: #5D4E37; font-size: 14px; font-weight: 600;">{{ ucfirst($subscription->metodo_pago ?? 'PayPal') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Features -->
<div style="margin-top: 25px;">
    <p style="margin: 0 0 15px 0; color: #5D4E37; font-size: 16px; font-weight: 600;">Ahora puedes disfrutar de:</p>
    <table role="presentation" cellspacing="0" cellpadding="0">
        @if($plan->mensajes_ilimitados)
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #22C55E; font-size: 18px; margin-right: 10px;">&#10003;</span>
                <span style="color: #666666; font-size: 14px;">Mensajes ilimitados</span>
            </td>
        </tr>
        @endif
        @if($plan->ver_quien_te_gusta)
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #22C55E; font-size: 18px; margin-right: 10px;">&#10003;</span>
                <span style="color: #666666; font-size: 14px;">Ver quién te ha dado like</span>
            </td>
        </tr>
        @endif
        @if($plan->likes_diarios == -1)
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #22C55E; font-size: 18px; margin-right: 10px;">&#10003;</span>
                <span style="color: #666666; font-size: 14px;">Likes ilimitados</span>
            </td>
        </tr>
        @endif
        @if($plan->sin_anuncios)
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #22C55E; font-size: 18px; margin-right: 10px;">&#10003;</span>
                <span style="color: #666666; font-size: 14px;">Sin anuncios</span>
            </td>
        </tr>
        @endif
    </table>
</div>
@endsection

@section('cta')
<a href="{{ config('app.url') }}/dashboard" style="display: inline-block; background: linear-gradient(135deg, #E94057 0%, #F27121 100%); color: #FFFFFF; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(233, 64, 87, 0.3);">
    Empezar a conocer gente
</a>
@endsection
