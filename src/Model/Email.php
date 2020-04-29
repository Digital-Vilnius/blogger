<?php

namespace App\Model;

class Email
{
    private $receiver;
    private $subject;
    private $message;

    public function __construct(string $receiver, string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->receiver = $receiver;
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

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
}