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
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        // Asegurar que la relación 'plan' esté cargada
        $this->subscription->load('plan');
        $plan = $this->subscription->plan;

        // Si por alguna razón el plan no está cargado, loguearlo
        if (!$plan) {
            \Log::error('SubscriptionActivatedNotification: Plan no encontrado', [
                'subscription_id' => $this->subscription->id,
                'plan_id' => $this->subscription->plan_id
            ]);
            throw new \Exception('No se pudo cargar el plan de la suscripción');
        }

        $mailable = (new MailMessage)
            ->subject('¡Tu suscripción a Citas Mallorca está activa!')
            ->view('emails.subscription-activated', [
                'user' => $notifiable,
                'subscription' => $this->subscription,
                'plan' => $plan,
            ]);

        return $mailable;
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
