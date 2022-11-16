<?php

namespace App\Listeners;

use App\Services\MailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserForgotPasswordListener
{
    public function handle($event)
    {
        $service = new MailService();
        $service->send('', 'Your new password ' .
            $event->password,
            $event->user->email,
            $event->user->name, 'Your new password'
        );
    }
}
