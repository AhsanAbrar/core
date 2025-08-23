<?php

namespace [[rootNamespace]]\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestMail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ?string $from = null)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->from($this->from ?? env('MAIL_FROM_ADDRESS'))
            ->line('Domain: '.request()->getHost())
            ->line('IP: '.request()->ip())
            ->line('This is a testing email for credentials verification for ahsandevs + thetheme')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using ahsandevs!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            //
        ];
    }
}
