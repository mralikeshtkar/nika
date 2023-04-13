<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kavenegar\Laravel\Message\KavenegarMessage;
use Kavenegar\Laravel\Notification\KavenegarBaseNotification;

class VerificationCodeNotification extends KavenegarBaseNotification
{
    public function toKavenegar($notifiable)
    {
        return (new KavenegarMessage())
            ->to($notifiable->mobile)
            ->verifyLookup('verify',[$notifiable->verification_code]);
    }
}
