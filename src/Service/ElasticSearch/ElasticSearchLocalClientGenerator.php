<?php

namespace App\Service\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticSearchLocalClientGenerator implements ElasticSearchClientGeneratorInterface
{
    private Client $client;

    public function __construct(string $elasticHost, string $elasticPort)
    {
        $this->client = ClientBuilder::create()
            ->setHosts([sprintf('http://%s:%s', $elasticHost, $elasticPort)])
            ->build();
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
