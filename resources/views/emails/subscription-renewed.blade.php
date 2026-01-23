@extends('emails.layout')

@section('title', 'Suscripción Renovada')

@section('icon')
<table role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td style="width: 80px; height: 80px; background: linear-gradient(135deg, #22C55E 0%, #16A34A 100%); border-radius: 50%; text-align: center; vertical-align: middle;">
            <span style="font-size: 36px; color: #FFFFFF;">&#8635;</span>
        </td>
    </tr>
</table>
@endsection

@section('heading')
    Suscripción Renovada
@endsection

@section('subheading')
    Tu pago se ha procesado correctamente
@endsection

@section('content')
<!-- Success Message -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #F0FDF4; border-radius: 12px; border-left: 4px solid #22C55E; margin-bottom: 25px;">
    <tr>
        <td style="padding: 20px;">
            <p style="margin: 0; color: #166534; font-size: 14px; line-height: 1.6;">
                <strong>¡Hola {{ $user->name }}!</strong><br><br>
                Hemos renovado tu suscripción <strong>{{ $plan->nombre }}</strong> correctamente. Puedes seguir disfrutando de todas las funcionalidades premium.
            </p>
        </td>
    </tr>
</table>

<!-- Payment Details -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #F8F4F0; border-radius: 12px; margin-bottom: 25px;">
    <tr>
        <td style="padding: 20px;">
            <p style="margin: 0 0 15px 0; color: #5D4E37; font-size: 16px; font-weight: 600;">Detalles del pago:</p>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #E8E0D8;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Plan</td>
                                <td align="right" style="color: #5D4E37; font-size: 14px; font-weight: 600;">{{ $plan->nombre }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #E8E0D8;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Importe cobrado</td>
                                <td align="right" style="color: #22C55E; font-size: 14px; font-weight: 600;">{{ number_format($subscription->monto_pagado, 2) }}€</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #E8E0D8;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Fecha de cobro</td>
                                <td align="right" style="color: #5D4E37; font-size: 14px; font-weight: 600;">{{ now()->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Próxima renovación</td>
                                <td align="right" style="color: #5D4E37; font-size: 14px; font-weight: 600;">{{ $subscription->fecha_expiracion->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Info -->
<p style="margin: 0; color: #888888; font-size: 13px; line-height: 1.6; text-align: center;">
    Puedes gestionar tu suscripción en cualquier momento desde tu panel de usuario.
</p>
@endsection

@section('cta')
<a href="{{ config('app.url') }}/mi-suscripcion" style="display: inline-block; background: linear-gradient(135deg, #E94057 0%, #F27121 100%); color: #FFFFFF; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(233, 64, 87, 0.3);">
    Ver mi suscripción
</a>
@endsection
