<?php

namespace App\Notifications\Channels;

use App\Notifications\ResetPasswordNotification;
use RuntimeException;

class MailtrapApiChannel
{
    public function send(object $notifiable, ResetPasswordNotification $notification): void
    {
        $message = $notification->toMail($notifiable);
        $recipient = $notifiable->getEmailForPasswordReset();
        $envelope = $message->envelope();
        $content = $message->content();
        $host = config('services.mailtrap-sdk.host', 'send.api.mailtrap.io');

        $payload = json_encode([
            'from' => [
                'email' => config('mail.from.address'),
                'name' => config('mail.from.name'),
            ],
            'to' => [['email' => $recipient]],
            'subject' => $envelope->subject,
            'html' => view($content->view, $content->with)->render(),
        ], JSON_THROW_ON_ERROR);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Api-Token: ".config('services.mailtrap-sdk.apiKey')."\r\n"
                    .'Accept: application/json' . "\r\n"
                    .'Content-Type: application/json' . "\r\n",
                'content' => $payload,
                'ignore_errors' => true,
                'timeout' => 15,
            ],
        ]);

        $body = file_get_contents("https://{$host}/api/send", false, $context);
        $status = 0;

        if (isset($http_response_header[0])) {
            preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches);
            $status = (int) ($matches[1] ?? 0);
        }

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException('Mailtrap rejected the password reset email: '.trim((string) $body));
        }
    }
}
