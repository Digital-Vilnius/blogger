<?php

namespace App\Service;

use App\Model\Sms;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SmsService implements SmsServiceInterface
{
    private $twilioClient;
    private $twilioNumber;
    private $urlGenerator;

    public function __construct(string $twilioNumber, Client $client, UrlGeneratorInterface $urlGenerator)
    {
        $this->twilioClient = $client;
        $this->twilioNumber = $twilioNumber;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Sms $sms
     * @return string
     * @throws TwilioException
     */
    public function send(Sms $sms): string
    {
        $response = $this->twilioClient->messages->create($sms->getReceiver(), [
            'from' => $this->twilioNumber,
            'body' => $sms->getMessage(),
            'statusCallback' => $this->urlGenerator->generate('admin twilio status')
        ]);

        return $response->sid;
    }
}