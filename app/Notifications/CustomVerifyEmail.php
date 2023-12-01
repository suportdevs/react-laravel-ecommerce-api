<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
    public function toMail($notifiable)
    {
        $frontendURL = env('FRONTEND_URL');
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $frontendURL . '/emai/verify/'. $notifiable->getKey().'/'. sha1($notifiable->getEmailForVerification()))
            ->line('If you did not create an account, no further action is required.')
            ->line('Regards,')
            ->line('Laravel')
            ->line("
            If you're having trouble clicking the 'Verify Email Address' button, copy and paste the URL below into your web browser: ". $frontendURL . "/api/email/verify/". $notifiable->getKey(). "/" . sha1($notifiable->getEmailForVerification()));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
