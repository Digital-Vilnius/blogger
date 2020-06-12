<?php

namespace App\Enum;

use ReflectionClass;

abstract class Channels
{
    public const SMS = 'SMS';
    public const EMAIL = 'EMAIL';

    public static function contains($channel)
    {
        $channels = (new ReflectionClass(Channels::class))->getConstants();
        foreach ($channels as $name => $value) if ($value == $channel) return $name;
        return null;
    }
}