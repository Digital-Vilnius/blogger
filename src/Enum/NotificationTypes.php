<?php

namespace App\Enum;

use ReflectionClass;

abstract class NotificationTypes
{
    public const ADD_LIKE = 'ADD_LIKE';
    public const ADD_COMMENT = 'ADD_COMMENT';
    public const ADD_SUBSCRIBER = 'ADD_SUBSCRIBER';

    public static function contains($notificationType)
    {
        $notificationTypes = (new ReflectionClass(NotificationTypes::class))->getConstants();
        foreach ($notificationTypes as $name => $value) if ($value == $notificationType) return $name;
        return null;
    }
}