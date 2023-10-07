<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeptReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /***
     *Create a new message instance.
     * @return void
     */

    private $user;
    
    public function __construct(User $user=null)
    {
        $this->user = $user;
    }

    /***
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->view('view.mail.deptReminder')->with([
            'name' => "{$this->user->client->firstName} {$this->user->client->lastName}",
            'amount_left' => $this->user->deptStatus->amount_left,
            'created_at' => $this->user->deptStatus->created_at
        ]);
    }

}
