<?php

namespace App\Notifier;

use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class PostNotification extends Notification implements EmailNotificationInterface
{
    public function getChannels(Recipient $recipient): array
    {
        return ['email'];
    }

    public function asEmailMessage(Recipient $recipient, string $transport = null): ?EmailMessage
    {

    }
}