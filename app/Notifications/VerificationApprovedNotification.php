<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Profile;

class VerificationApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $profile;

    /**
     * Create a new notification instance.
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
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
            ->subject('Identidad verificada - Citas Mallorca')
            ->greeting('Hola, ' . $this->profile->nombre)
            ->line('Tu identidad ha sido verificada correctamente.')
            ->line('Tu cuenta está ahora completamente activada y no será marcada como sospechosa.')
            ->action('Ver mi perfil', route('user.profile.show'))
            ->line('¡Gracias por formar parte de Citas Mallorca!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'verification_approved',
            'title' => 'Identidad verificada',
            'message' => 'Tu cuenta ha sido verificada correctamente',
            'profile_id' => $this->profile->id,
            'icon' => '✓',
            'url' => route('user.profile.show'),
        ];
    }
}
