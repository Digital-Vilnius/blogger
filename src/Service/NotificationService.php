<?php

namespace App\Service;

use App\Entity\Notification;
use App\Enum\NotificationTypes;
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
        switch ($notification->getType()) {
            case NotificationTypes::ADD_SUBSCRIBER:
            {
                if ($notification->getEmail()) {
                    $email = new Email($notification->getUser()->getEmail(), 'New subscriber', 'New subscriber');
                    $this->emailService->send($email);
                }

                if ($notification->getSms()) {
                    $sms = new Sms($notification->getUser()->getPhone(), 'New subscriber');
                    $this->smsService->send($sms);
                }
            }
        }
    }
}