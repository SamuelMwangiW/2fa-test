<?php

namespace App\Providers;

use App\Listeners\SendTwoFactorCodeListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Fortify\Events\TwoFactorAuthenticationEnabled;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TwoFactorAuthenticationChallenged::class => [
            SendTwoFactorCodeListener::class,
        ],
        TwoFactorAuthenticationEnabled::class => [
            SendTwoFactorCodeListener::class,
        ],
    ];
}
