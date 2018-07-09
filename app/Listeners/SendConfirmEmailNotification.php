<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\Welcome;
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
        if(!$event->getUser()->verification_code){
            $event->getUser()->createVerificationCode();
        }

        $token = $event->getUser()->verification_code;
        Mail::to($event->getUser()->email)->send(new Welcome($event->getUser(), $token));
    }
}
