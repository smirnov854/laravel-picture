<?php

namespace App\Listeners;

use App\Events\UserRegister;
use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserEmailNotification
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        $user = $event->user;
        $service = new MailService();
        $service->send('',
            '<a href="">confirm email hash ' . $user->email_verification_hash . '</a>',
            $user->email,
            $user->name,
            'Confirm email');
        //
    }
}
