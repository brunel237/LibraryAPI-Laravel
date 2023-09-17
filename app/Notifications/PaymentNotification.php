<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $payment_id, $amount_paid, $amount_left;

    public function __construct($payment_id, $amount_paid, $amount_left)
    {
        $this->payment_id = $payment_id;
        $this->amount_paid = $amount_paid;
        $this->amount_left = $amount_left;
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

    public function toDatabase($notifiable)
    {
        return [
            "message" => "You just repaid your dept.",
            "payment_id" => $this->payment_id,
            "amount_paid" => $this->amount_paid,
            "amount_left" => $this->amount_left,
        ];
    }
}
