<?php

namespace App\Notifications;

use App\Mail\ResetPasswordMail;
use App\Notifications\Channels\MailtrapApiChannel;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly string $token,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [MailtrapApiChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): Mailable
    {
        return (new ResetPasswordMail(
            $this->token,
            $notifiable->getEmailForPasswordReset(),
        ))->to($notifiable->getEmailForPasswordReset());
    }
}
