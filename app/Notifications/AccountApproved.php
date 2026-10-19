<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AccountApproved extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('БиблиоМ – налог је одобрен')
            ->greeting('Добро дошли, '.$notifiable->name.'!')
            ->line('Администратор је одобрио вашу регистрацију.')
            ->line('Потврдите своју мејл адресу да бисте приступили налогу.')
            ->action('Потврди мејл и приступи налогу', $this->verificationUrl($notifiable))
            ->line('Линк важи '.config('auth.verification.expire', 60).' минута.')
            ->salutation('Срдачно');
    }
}
