<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UserSubscription;

class SubscriptionActivatedNotification extends Notification
{
    use Queueable;

    public $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct(UserSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $plan = $this->subscription->plan;
        $montoPagado = number_format($this->subscription->monto_pagado, 2);
        $fechaExpiracion = $this->subscription->fecha_expiracion->format('d/m/Y');

        return (new MailMessage)
            ->subject('¡Tu suscripción a Citas Mallorca está activa! 💕')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu pago se ha procesado correctamente y tu suscripción ya está activa.')
            ->line('**Plan:** ' . $plan->nombre)
            ->line('**Tipo:** ' . ucfirst($this->subscription->tipo))
            ->line('**Monto pagado:** ' . $montoPagado . '€')
            ->line('**Válido hasta:** ' . $fechaExpiracion)
            ->action('Ir a Mi Perfil', url('/mi-perfil'))
            ->line('Ahora puedes disfrutar de todas las funcionalidades premium:')
            ->line('✓ ' . ($plan->mensajes_ilimitados ? 'Mensajes ilimitados' : 'Mensajes semanales a usuarios gratuitos'))
            ->line('✓ ' . ($plan->puede_iniciar_conversacion ? 'Iniciar conversaciones' : 'Responder mensajes'))
            ->line('✓ Ver quién te ha dado like')
            ->line('✓ Super Likes ilimitados')
            ->line('')
            ->line('Si tienes alguna pregunta, no dudes en contactarnos.')
            ->salutation('Equipo de Citas Mallorca');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'plan_name' => $this->subscription->plan->nombre,
            'amount' => $this->subscription->monto_pagado,
        ];
    }
}
