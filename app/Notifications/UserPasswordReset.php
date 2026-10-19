<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class UserPasswordReset extends ResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('БиблиоМ – промјена лозинке')
            ->greeting('Здраво!')
            ->line('Примили смо захтјев за промјену лозинке вашег налога.')
            ->action('Промијени лозинку', $url)
            ->line('Линк важи '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' минута.')
            ->line('Ако нисте тражили промјену лозинке, занемарите ову поруку.')
            ->salutation('Срдачно');
    }
}
