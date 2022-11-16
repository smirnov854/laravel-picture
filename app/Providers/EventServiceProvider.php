<?php

namespace App\Providers;

use App\Events\UserForgotPassword;
use App\Events\UserRegister;
use App\Listeners\UserEmailNotification;
use App\Listeners\UserForgotPasswordListener;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserRegister::class=>[
            UserEmailNotification::class,
        ],
        UserForgotPassword::class=>[
            UserForgotPasswordListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //User::observe(new UserObserver());
    }
}
