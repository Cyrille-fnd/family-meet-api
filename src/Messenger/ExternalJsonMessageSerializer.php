<?php

namespace App\Messenger;

use App\Message\RegisteredUserEvent;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessageSerializer implements SerializerInterface
{
    /**
     * @param array<string, string> $encodedEnvelope
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        /** @var array<string, string> $data */
        $data = json_decode($body, true);
        $message = new RegisteredUserEvent($data['userId']);

        return new Envelope($message);
    }

    /**
     * @return array<string, array<string, bool|string>|string|false>
     */
    public function encode(Envelope $envelope): array
    {
        // this is called if a message is redelivered for "retry"
        $message = $envelope->getMessage();
        // expand this logic later if you handle more than
        // just one message class
        if ($message instanceof RegisteredUserEvent) {
            // recreate what the data originally looked like
            $data = ['userId' => $message->getUserId()];
        } else {
            throw new \Exception('Unsupported message class');
        }
        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            // to avoid phpstan problems
            if (!is_array($stamps)) {
                continue;
            }
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => json_encode($data),
            'headers' => [
                // store stamps as a header - to be read in decode()
                'stamps' => json_encode($allStamps),
            ],
        ];
    }
}
