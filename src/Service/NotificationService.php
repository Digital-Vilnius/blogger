<?php

namespace App\Service;

use App\Entity\Notification;
use App\Enum\Channels;
use App\Model\Email;
use App\Model\Sms;

class NotificationService implements NotificationServiceInterface
{
    private $emailService;
    private $smsService;

    public function __construct(EmailServiceInterface $emailService, SmsServiceInterface $smsService)
    {
        $this->emailService = $emailService;
        $this->smsService = $smsService;
    }

    public function notify(Notification $notification): void
    {
        $subscriber = $notification->getSubscriber();
        switch ($notification->getChannel()) {
            case Channels::SMS:
            {
                if ($subscriber->getSmsNotification()) {
                    $sms = new Sms($notification->getSubscriber()->getPhone(), $notification->getContent());
                    $notification->setTwilioSid($this->smsService->send($sms));
                    break;
                }
            }
            case Channels::EMAIL:
            {
                if ($subscriber->getEmailNotification()) {
                    $email = new Email($notification->getSubscriber()->getEmail(), $notification->getTitle(), $notification->getHtmlContent());
                    $this->emailService->send($email);
                    break;
                }
            }
        }
    }
}