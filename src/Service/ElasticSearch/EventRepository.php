<?php

namespace App\Service\ElasticSearch;

use App\Dto\GetEventDTO;
use Elastica\Client;
use Elastica\Request;

class EventRepository
{
    public const ALLOWED_CRITERIAS = [
        'hostId' => 'hostId',
        'guestId' => 'guests',
    ];

    private Client $client;

    public function __construct(ElasticaClientGenerator $clientGenerator)
    {
        $this->client = $clientGenerator->getClient();
    }

    /**
     * @return GetEventDTO[]
     */
    public function findAll(): array
    {
        $index = $this->client->getIndex('familymeet');
        $path = $index->getName().'/_search';
        $query = '{"query": {"match_all": {}}}';

        /** @var array<string, array<string, string|array<string, int|string|array<int, string>>>> $hits */
        $hits = $this->client->request($path, Request::GET, $query)->getData()['hits'];
        /** @var array<string, array<string,string>|array<string, int|string|array<int, string>>> $results */
        $results = $hits['hits'];

        $events = [];
        foreach ($results as $result) {
            /** @var string $id */
            $id = $result['_id'];

            /** @var array<string, int|string|array<int, string>> $source */
            $source = $result['_source'];
            $data = array_merge(['id' => $id], $source);

            $events[] = GetEventDTO::fromArray($data);
        }

        return $events;
    }

    /**
     * @param array<string, bool|float|int|string|null> $criterias
     *
     * @return GetEventDTO[]
     */
    public function findBy(array $criterias): array
    {
        $index = $this->client->getIndex('familymeet');
        $path = $index->getName().'/_search';

        $match = '';

        foreach ($criterias as $criteriaKey => $criteriaValue) {
            if (!array_key_exists($criteriaKey, self::ALLOWED_CRITERIAS)) {
                continue;
            }

            if ('' !== $match) {
                $match .= ',';
            }

            $match .= sprintf('{"match":{"%s": "%s"}}', self::ALLOWED_CRITERIAS[$criteriaKey], $criteriaValue);
        }

        $query = sprintf('{"query": {"bool": {"must":[%s]}}}', $match);

        /** @var array<string, array<string, string|array<string, int|string|array<int, string>>>> $hits */
        $hits = $this->client->request($path, Request::GET, $query)->getData()['hits'];
        /** @var array<string, array<string,string>|array<string, int|string|array<int, string>>> $results */
        $results = $hits['hits'];

        $events = [];
        foreach ($results as $result) {
            $id = $result['_id'];

            /** @var array<string, int|string|array<int, string>> $source */
            $source = $result['_source'];
            $data = array_merge(['id' => $id], $source);

            $events[] = GetEventDTO::fromArray($data);
        }

        return $events;
    }
}
