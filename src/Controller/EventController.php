<?php

namespace App\Controller;

use App\Dto\CreateEventDTO;
use App\Dto\UpdateEventDTO;
use App\Entity\User;
use App\Service\ElasticSearch\ElasticSearchClientGeneratorInterface;
use App\Service\ElasticSearch\EventRepository;
use App\Utils\ArrayConverterTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class EventController extends AbstractController
{
    use ArrayConverterTrait;

    #[Route('/v1/api/events', name: 'v1_api_events_post', methods: ['POST'])]
    public function post(
        Request $request,
        EntityManagerInterface $entityManager,
        ElasticSearchClientGeneratorInterface $clientGenerator
    ): JsonResponse {
        if (null === $request->query->get('hostId')) {
            return new JsonResponse(
                [
                    'code' => 'host_id_not_provided',
                    'message' => 'host id not provided',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var string $hostId */
        $hostId = $request->query->get('hostId');

        $host = $entityManager->getRepository(User::class)->find($hostId);

        if (null === $host) {
            return new JsonResponse(
                [
                    'code' => 'user_not_found',
                    'message' => 'user not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $payload = $request->getPayload();

        /** @var string $title */
        $title = $payload->get('title');
        /** @var string $location */
        $location = $payload->get('location');
        /** @var string $date */
        $date = $payload->get('date');
        /** @var string $category */
        $category = $payload->get('category');
        /** @var int $participantMax */
        $participantMax = $payload->get('participantMax');

        $eventDTO = new CreateEventDTO(
            Uuid::v4()->jsonSerialize(),
            $title,
            $location,
            $date,
            $category,
            $participantMax,
            new \DateTime(),
            $hostId,
        );

        $client = $clientGenerator->getClient();

        $params = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => $eventDTO->getId(),
            'body' => [
                'title' => $eventDTO->getTitle(),
                'location' => $eventDTO->getLocation(),
                'date' => $eventDTO->getDate(),
                'category' => $eventDTO->getCategory(),
                'participantMax' => $eventDTO->getParticipantMax(),
                'createdAt' => $eventDTO->getCreatedAt()->format('Y-m-d h:i:s'),
                'hostId' => $eventDTO->getHostId(),
                'guests' => $eventDTO->getGuests(),
            ],
        ];

        try {
            $client->index($params);
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'code' => 'bad_request',
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse($eventDTO->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('v1/api/events/{id}', name: 'v1_api_events_get_by_id', methods: ['GET'])]
    public function getById(
        string $id,
        ElasticSearchClientGeneratorInterface $clientGenerator
    ): JsonResponse {
        $client = $clientGenerator->getClient();

        $params = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => $id,
        ];

        try {
            /** @var array<string, array<int, string>|int|string> $eventData */
            $eventData = $client->get($params)['_source'];
            $eventDTO = CreateEventDTO::fromArray(array_merge(['id' => $id], $eventData));
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'code' => 'bad_request',
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse($eventDTO->jsonSerialize());
    }

    #[Route('v1/api/events', name: 'v1_api_events_get', methods: ['GET'])]
    public function get(
        Request $request,
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository
    ): JsonResponse {
        $hostId = $request->query->get('hostId');
        $guestId = $request->query->get('guestId');
        /** @var int|null $page */
        $page = $request->query->get('page');

        if (null === $hostId && null === $guestId) {
            $events = $eventRepository->findAll($page);

            return new JsonResponse(self::toArray($events));
        }

        if (null !== $hostId) {
            $host = $entityManager->getRepository(User::class)->find($hostId);

            if (null === $host) {
                return new JsonResponse(
                    [
                        'code' => 'host_not_found',
                        'message' => 'host not found',
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        if (null !== $guestId) {
            $guest = $entityManager->getRepository(User::class)->find($guestId);

            if (null === $guest) {
                return new JsonResponse(
                    [
                        'code' => 'guest_not_found',
                        'message' => 'guest not found',
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        $events = $eventRepository->findBy(array_filter([
            'hostId' => $hostId,
            'guestId' => $guestId,
        ]));

        return new JsonResponse(self::toArray($events));
    }

    #[Route('v1/api/events/{id}', name: 'events_put', methods: ['PUT'])]
    public function put(
        string $id,
        ElasticSearchClientGeneratorInterface $clientGenerator,
        Request $request
    ): JsonResponse {
        $client = $clientGenerator->getClient();

        $payload = $request->getPayload();

        /** @var string $title */
        $title = $payload->get('title');
        /** @var string $location */
        $location = $payload->get('location');
        /** @var string $date */
        $date = $payload->get('date');
        /** @var string $category */
        $category = $payload->get('category');
        /** @var int $participantMax */
        $participantMax = $payload->get('participantMax');

        $eventDTO = new UpdateEventDTO(
            $id,
            $title,
            $location,
            $date,
            $category,
            $participantMax,
        );

        $params = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => $eventDTO->getId(),
            'body' => [
                'doc' => [
                    'title' => $eventDTO->getTitle(),
                    'location' => $eventDTO->getLocation(),
                    'date' => $eventDTO->getDate(),
                    'category' => $eventDTO->getCategory(),
                    'participantMax' => $eventDTO->getParticipantMax(),
                ],
            ],
        ];

        try {
            $client->update($params);
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'code' => 'bad request',
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse($eventDTO->jsonSerialize());
    }

    #[Route('v1/api/events/{id}', name: 'events_delete', methods: ['DELETE'])]
    public function delete(
        string $id,
        ElasticSearchClientGeneratorInterface $clientGenerator
    ): JsonResponse {
        $client = $clientGenerator->getClient();
        $params = [
            'index' => $_ENV['ELASTICSEARCH_INDEX_NAME'],
            'id' => $id,
        ];

        try {
            $client->delete($params);
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'code' => 'bad request',
                    'message' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
