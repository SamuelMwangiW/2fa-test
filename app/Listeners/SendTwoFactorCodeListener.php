<?php

namespace App\Listeners;

use App\Notifications\SendOTP;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Fortify\Events\TwoFactorAuthenticationEnabled;

class SendTwoFactorCodeListener
{
    public function handle(
        TwoFactorAuthenticationChallenged|TwoFactorAuthenticationEnabled $event
    ): void {
        $event->user->notify(app(SendOTP::class));
    }
}
