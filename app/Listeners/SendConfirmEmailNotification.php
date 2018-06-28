<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\EmailVerification;
use Mail;

class SendConfirmEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserRegistered $event
     *
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $token = $event->getUser()->createVerificationCode();
        Mail::to($event->getUser()->email)->send(new EmailVerification($event->getUser(),$token));
    }
}
