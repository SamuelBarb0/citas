<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UserSubscription;

class PaymentFailedNotification extends Notification
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

        return (new MailMessage)
            ->subject('⚠️ Problema con tu pago de Citas Mallorca')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Hemos detectado un problema al procesar tu último pago.')
            ->line('**Plan:** ' . $plan->nombre)
            ->line('**Estado:** Pago rechazado')
            ->line('')
            ->line('Tu acceso a las funcionalidades premium ha sido suspendido temporalmente.')
            ->line('Por favor, actualiza tu método de pago para continuar disfrutando del servicio.')
            ->action('Actualizar Método de Pago', route('subscriptions.dashboard'))
            ->line('Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.')
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
        ];
    }
}
