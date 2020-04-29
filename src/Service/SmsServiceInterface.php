<?php

namespace App\Service;

use App\Model\Sms;

interface SmsServiceInterface
{
    public function send(Sms $sms): void;
}