<?php

namespace App\Service\ElasticSearch;

use App\Dto\GetEventDTO;
use Elasticsearch\Client;

class EventRepository
{
    public const ALLOWED_CRITERIAS = [
        'hostId' => 'hostId',
        'guestId' => 'guests',
    ];

    private Client $client;

    private int $nbResultsPerPage;

    public function __construct(
        ElasticSearchClientGeneratorInterface $clientGenerator,
        int $nbResultsPerPage
    ) {
        $this->client = $clientGenerator->getClient();
        $this->nbResultsPerPage = $nbResultsPerPage;
    }

    /**
     * @return GetEventDTO[]
     */
    public function findAll(?int $page): array
    {
        $page = $page ?? 1;
        $from = ($page * $this->nbResultsPerPage) - $this->nbResultsPerPage;

        $jsonQuery = sprintf(
            '{"from": %d,"size": %d,"query": {"match_all": {}},"sort":[{"date":"asc"}]}',
            $from,
            $this->nbResultsPerPage
        );

        $params = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'body' => $jsonQuery,
        ];

        $results = $this->client->search($params)['hits']['hits'];

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
        $match = '';

        foreach ($criterias as $criteriaKey => $criteriaValue) {
            if (!array_key_exists($criteriaKey, self::ALLOWED_CRITERIAS)) {
                continue;
            }

            if ('' !== $match) {
                $match .= ',';
            }

            $match .= sprintf('{"term":{"%s.keyword": "%s"}}', self::ALLOWED_CRITERIAS[$criteriaKey], $criteriaValue);
        }

        $jsonQuery = sprintf('{"query": {"bool": {"filter":[%s]}}}', $match);

        $params = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'body' => $jsonQuery,
        ];

        $results = $this->client->search($params)['hits']['hits'];

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
