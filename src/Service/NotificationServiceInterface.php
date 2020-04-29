<?php

namespace App\Service;

use App\Entity\Notification;
use Doctrine\Common\Collections\ArrayCollection;

interface NotificationServiceInterface
{
    public function notify(Notification $notification): void;
}