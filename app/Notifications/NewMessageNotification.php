<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;
use App\Models\User;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message, User $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
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
        $senderName = $this->sender->profile->nombre ?? $this->sender->name;
        $preview = \Illuminate\Support\Str::limit($this->message->mensaje, 50);

        return (new MailMessage)
            ->subject('Nuevo mensaje de ' . $senderName . ' 💬')
            ->greeting('¡Hola!')
            ->line('Tienes un nuevo mensaje de ' . $senderName . ':')
            ->line('"' . $preview . '"')
            ->action('Leer Mensaje', route('messages.show', $this->message->match_id))
            ->line('¡No dejes esperando a tu match!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_message',
            'message_id' => $this->message->id,
            'match_id' => $this->message->match_id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->profile->nombre ?? $this->sender->name,
            'sender_photo' => $this->sender->profile->foto_principal ?? null,
            'message_preview' => \Illuminate\Support\Str::limit($this->message->mensaje, 50),
            'message' => 'Nuevo mensaje de ' . ($this->sender->profile->nombre ?? $this->sender->name),
        ];
    }
}
