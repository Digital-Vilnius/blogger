<?php

namespace App\Service;

use App\Model\Email;

interface EmailServiceInterface
{
    public function send(Email $email): void;
}