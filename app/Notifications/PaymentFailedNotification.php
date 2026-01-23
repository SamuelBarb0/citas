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
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        $plan = $this->subscription->plan;

        $mailable = (new MailMessage)
            ->subject('Problema con tu pago - Citas Mallorca')
            ->view('emails.payment-failed', [
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
        ];
    }
}
