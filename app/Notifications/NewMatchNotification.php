<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewMatchNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $matchedUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $matchedUser)
    {
        $this->matchedUser = $matchedUser;
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
            ->subject('¡Tienes un nuevo match! 💕')
            ->greeting('¡Felicidades!')
            ->line('¡Tienes un nuevo match con ' . $this->matchedUser->profile->nombre . '!')
            ->line('Os habéis gustado mutuamente. Ahora podéis empezar a chatear.')
            ->action('Ver Match', route('matches'))
            ->line('¡No seas tímido y envía el primer mensaje!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_match',
            'matched_user_id' => $this->matchedUser->id,
            'matched_user_name' => $this->matchedUser->profile->nombre ?? $this->matchedUser->name,
            'matched_user_photo' => $this->matchedUser->profile->foto_principal ?? null,
            'message' => 'Tienes un nuevo match con ' . ($this->matchedUser->profile->nombre ?? $this->matchedUser->name),
        ];
    }
}
