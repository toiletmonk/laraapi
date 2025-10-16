<?php

namespace App;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendMessage($number, $message)
    {
        return $this->client->messages->create($number, [
            'from'=>config('services.twilio.from'),
            'body'=>$message
        ]);
    }
}
