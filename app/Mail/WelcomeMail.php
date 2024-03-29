<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $name, $username, $email;
    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.mail.welcome')->with([
            'email' => $this->user->client->email,
            'name' => "{$this->user->client->firstName} {$this->user->client->lastName}",
            'username' => $this->user->username
        ]);
    }

}
