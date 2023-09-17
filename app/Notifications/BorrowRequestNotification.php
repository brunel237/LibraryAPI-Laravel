<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $request, $message;

    public function __construct($request, $message)
    {
        $this->request = $request;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    public function toDatabase($notifiable)
    {
        return [
            "message" => $this->message,
            "article" => $this->request->book->type,
            "title" => $this->request->book->title,
            "quantity_borrowed" => $this->request->quantity,
            "payment_id" => $this->request->payment_id,
            // "user_status" => "Ineligible",
        ];
    }
}
