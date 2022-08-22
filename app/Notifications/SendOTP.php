<?php

namespace App\Notifications;

use App\Actions\TwoFactor\GenerateOTP;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use SamuelMwangiW\Africastalking\Notifications\AfricastalkingChannel;

class SendOTP extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(User $notifiable): array
    {
        return [AfricastalkingChannel::class, 'mail'];
    }

    public function toAfricastalking(User $notifiable): string
    {
        return "Hi {$notifiable->name}. Your login security code is {$this->getTwoFactorCode($notifiable)}";
    }

    public function toMail(User $notifiable)
    {
        return (new MailMessage)
            ->line('Your security code is ' . $this->getTwoFactorCode($notifiable))
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(User $notifiable)
    {
        return [
            //
        ];
    }

    /**
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws SecretKeyTooShortException
     * @throws InvalidCharactersException
     */
    public function getTwoFactorCode(User $notifiable): ?string
    {
        if (!$notifiable->two_factor_secret) {
            return null;
        }

        return GenerateOTP::for(
            decrypt($notifiable->two_factor_secret)
        );
    }
}
