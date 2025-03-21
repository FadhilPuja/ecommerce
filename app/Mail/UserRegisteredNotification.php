<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the email message.
     */
    public function build()
    {
        return $this->from($this->user->email, $this->user->name)
                    ->subject('New User Registered')
                    ->view('emails.user_registered_text')
                    ->with([
                        'userName'  => $this->user->name,
                        'userEmail' => $this->user->email,
                    ]);
    }
}
