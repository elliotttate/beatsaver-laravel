<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Welcome extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var string
     */
    protected $token;

    /**
     * Create a new message instance.
     *
     * @param User   $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(config('app.name').": Verify your email")
            ->markdown('master.mail-welcome', ['user' => $this->user, 'token' => $this->token]);
    }
}
