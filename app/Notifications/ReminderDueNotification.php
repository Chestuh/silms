<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderDueNotification extends Notification
{
    use Queueable;


    protected $reminder;

    /**
     * Create a new notification instance.
     */
    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */

    // Not used for in-app notification
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Reminder: ' . ($this->reminder->title ?? ''))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->reminder->title,
            'remind_at' => $this->reminder->remind_at,
            'material_id' => $this->reminder->material_id,
        ];
    }
}
