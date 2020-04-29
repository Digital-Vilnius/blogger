<?php

namespace App\Model;

class Sms
{
    private $receiver;
    private $message;

    public function __construct(string $receiver, string $message)
    {
        $this->receiver = $receiver;
        $this->message = $message;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function setReceiver(string $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}