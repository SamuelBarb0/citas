<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginAttemptNotification extends Notification
{
    use Queueable;

    public string $ipAddress;
    public string $userAgent;
    public string $loginTime;
    public bool $successful;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $ipAddress, string $userAgent, bool $successful = true)
    {
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->successful = $successful;
        $this->loginTime = now()->format('d/m/Y H:i:s');
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
        $subject = $this->successful
            ? 'Nuevo inicio de sesión en tu cuenta - Citas Mallorca'
            : 'Intento de inicio de sesión fallido - Citas Mallorca';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hola ' . $notifiable->name . ',');

        if ($this->successful) {
            $message->line('Se ha iniciado sesión en tu cuenta de Citas Mallorca.')
                ->line('**Detalles del inicio de sesión:**')
                ->line('🕐 Fecha y hora: ' . $this->loginTime)
                ->line('🌐 Dirección IP: ' . $this->ipAddress)
                ->line('💻 Navegador: ' . $this->getBrowserName($this->userAgent))
                ->line('Si fuiste tú, puedes ignorar este mensaje.')
                ->line('Si **NO** reconoces este inicio de sesión, te recomendamos cambiar tu contraseña inmediatamente.')
                ->action('Cambiar contraseña', url('/mi-perfil'));
        } else {
            $message->line('Se ha detectado un intento de inicio de sesión **fallido** en tu cuenta.')
                ->line('**Detalles del intento:**')
                ->line('🕐 Fecha y hora: ' . $this->loginTime)
                ->line('🌐 Dirección IP: ' . $this->ipAddress)
                ->line('💻 Navegador: ' . $this->getBrowserName($this->userAgent))
                ->line('Si **NO** fuiste tú, te recomendamos cambiar tu contraseña por seguridad.')
                ->action('Cambiar contraseña', url('/mi-perfil'));
        }

        $message->line('Gracias por usar Citas Mallorca.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'login_time' => $this->loginTime,
            'successful' => $this->successful,
        ];
    }

    /**
     * Extract browser name from user agent
     */
    private function getBrowserName(string $userAgent): string
    {
        if (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            return 'Opera';
        }

        return 'Navegador desconocido';
    }
}
