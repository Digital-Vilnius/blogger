<?php

namespace App\Service;

use App\Model\Sms;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SmsService implements SmsServiceInterface
{
    private $twilioClient;
    private $twilioNumber;

    public function __construct(string $twilioNumber, Client $client)
    {
        $this->twilioClient = $client;
        $this->twilioNumber = $twilioNumber;
    }

    /**
     * @param Sms $sms
     * @throws TwilioException
     */
    public function send(Sms $sms): void
    {
        $this->twilioClient->messages->create($sms->getReceiver(), [
            'from' => $this->twilioNumber,
            'body' => $sms->getMessage()
        ]);
    }
}