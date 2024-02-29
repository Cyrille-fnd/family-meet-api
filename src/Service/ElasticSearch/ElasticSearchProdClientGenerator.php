<?php

namespace App\Service\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticSearchProdClientGenerator implements ElasticSearchClientGeneratorInterface
{
    private Client $client;

    public function __construct(string $elasticCloudId, string $elasticApiId, string $elasticApiKey)
    {
        $this->client = ClientBuilder::create()
            ->setElasticCloudId($elasticCloudId)
            ->setApiKey($elasticApiId, $elasticApiKey)
            ->build();
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
