<?php

namespace App\Service;

use App\Model\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MailerEmail;

class EmailService implements EmailServiceInterface
{
    private $mailer;
    private $systemEmail;

    public function __construct($systemEmail, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->systemEmail = $systemEmail;
    }

    /**
     * @param Email $email
     * @throws TransportExceptionInterface
     */
    public function send(Email $email): void
    {
        $mailerEmail = new MailerEmail();
        $mailerEmail->subject($email->getSubject());
        $mailerEmail->from($this->systemEmail);
        $mailerEmail->text($email->getMessage());
        $mailerEmail->to($email->getReceiver());
        $response = $this->mailer->send($mailerEmail);

        dump($response);
        die();
    }
}