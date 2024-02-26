<?php

namespace App\Service\ElasticSearch;

use Elastica\Client;

class ElasticaClientGenerator
{
    private Client $client;

    public function __construct(string $elasticHost, int $elasticPort)
    {
        $this->client = new Client([
            'host' => $elasticHost,
            'port' => $elasticPort,
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
