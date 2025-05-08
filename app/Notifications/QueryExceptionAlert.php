<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class QueryExceptionAlert extends Notification
{
    use Queueable;

    protected string $message;
    protected string $url;
    protected array $payload;

    public function __construct($exception, $url, $payload)
    {
        $this->message = $exception->getMessage();
        $this->url = $url;
        $this->payload = $payload;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('QueryException Alert')
            ->line('A QueryException has occurred.')
            ->line("Message: {$this->message}")
            ->line("URL: {$this->url}")
            ->line("Payload: " . json_encode($this->payload));
    }
}
