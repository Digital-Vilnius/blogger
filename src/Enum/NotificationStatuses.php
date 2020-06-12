<?php

namespace App\Enum;

use ReflectionClass;

abstract class NotificationStatuses
{
    public const SEND = 'SEND';
    public const PENDING = 'PENDING';
    public const FAILED = 'FAILED';

    public static function contains($notificationStatus)
    {
        $notificationStatuses = (new ReflectionClass(NotificationTypes::class))->getConstants();
        foreach ($notificationStatuses as $name => $value) if ($value == $notificationStatus) return $name;
        return null;
    }
}