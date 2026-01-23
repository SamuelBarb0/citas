@extends('emails.layout')

@section('title', 'Problema con tu pago')

@section('icon')
<table role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td style="width: 80px; height: 80px; background-color: #FEE2E2; border-radius: 50%; text-align: center; vertical-align: middle;">
            <span style="font-size: 36px; color: #DC2626;">!</span>
        </td>
    </tr>
</table>
@endsection

@section('heading')
    Problema con tu pago
@endsection

@section('subheading')
    No pudimos procesar el cobro de tu suscripción
@endsection

@section('content')
<!-- Alert Box -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #FEF2F2; border-radius: 12px; border-left: 4px solid #DC2626; margin-bottom: 25px;">
    <tr>
        <td style="padding: 20px;">
            <p style="margin: 0; color: #991B1B; font-size: 14px; line-height: 1.6;">
                <strong>Hola {{ $user->name }},</strong><br><br>
                Hemos intentado procesar el pago de tu suscripción <strong>{{ $plan->nombre }}</strong> pero no ha sido posible completarlo.
            </p>
        </td>
    </tr>
</table>

<!-- Subscription Info -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #F8F4F0; border-radius: 12px; margin-bottom: 25px;">
    <tr>
        <td style="padding: 20px;">
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
                                <td style="color: #888888; font-size: 14px;">Importe pendiente</td>
                                <td align="right" style="color: #DC2626; font-size: 14px; font-weight: 600;">{{ number_format($subscription->monto_pagado, 2) }}€</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="color: #888888; font-size: 14px;">Estado</td>
                                <td align="right" style="color: #DC2626; font-size: 14px; font-weight: 600;">Pago rechazado</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- What to do -->
<div style="margin-bottom: 25px;">
    <p style="margin: 0 0 15px 0; color: #5D4E37; font-size: 16px; font-weight: 600;">¿Qué puedes hacer?</p>
    <table role="presentation" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #5D4E37; font-size: 18px; margin-right: 10px;">1.</span>
                <span style="color: #666666; font-size: 14px;">Verifica que tu método de pago tiene fondos suficientes</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #5D4E37; font-size: 18px; margin-right: 10px;">2.</span>
                <span style="color: #666666; font-size: 14px;">Comprueba que los datos de tu tarjeta estén actualizados en PayPal</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">
                <span style="color: #5D4E37; font-size: 18px; margin-right: 10px;">3.</span>
                <span style="color: #666666; font-size: 14px;">Contacta con tu banco si el problema persiste</span>
            </td>
        </tr>
    </table>
</div>

<!-- Warning -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #FEF3C7; border-radius: 12px;">
    <tr>
        <td style="padding: 15px 20px;">
            <p style="margin: 0; color: #92400E; font-size: 13px; line-height: 1.5;">
                <strong>Importante:</strong> Tu acceso premium se ha suspendido temporalmente. Actualiza tu método de pago para recuperar todas las funcionalidades.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('cta')
<a href="{{ config('app.url') }}/mi-suscripcion" style="display: inline-block; background: linear-gradient(135deg, #E94057 0%, #F27121 100%); color: #FFFFFF; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(233, 64, 87, 0.3);">
    Actualizar método de pago
</a>
@endsection
