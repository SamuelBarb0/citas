<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Profile;

class VerificationRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $profile;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Profile $profile, string $reason)
    {
        $this->profile = $profile;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verificación de identidad - Acción requerida')
            ->greeting('Hola, ' . $this->profile->nombre)
            ->line('Tu solicitud de verificación de identidad no pudo ser aprobada.')
            ->line('Motivo: ' . $this->reason)
            ->line('Por favor, envía una nueva foto que cumpla con los requisitos. Si no completas este proceso, tu cuenta podría ser suspendida.')
            ->action('Enviar nueva foto de verificación', route('verification.create'))
            ->line('Si tienes dudas, no dudes en contactarnos.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'verification_rejected',
            'title' => 'Verificación rechazada',
            'message' => 'Debes enviar una nueva foto de verificación',
            'reason' => $this->reason,
            'profile_id' => $this->profile->id,
            'icon' => '✗',
            'url' => route('verification.create'),
        ];
    }
}
