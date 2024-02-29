<?php

namespace App\Service\ElasticSearch;

use Elasticsearch\Client;

interface ElasticSearchClientGeneratorInterface
{
    public function getClient(): Client;
}
