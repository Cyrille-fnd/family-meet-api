<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Event\EventCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class EventController extends AbstractController
{
    #[Route('/v1/api/events', name: 'v1_api_events_post', methods: ['POST'])]
    public function post(
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ): JsonResponse {
        $hostId = $request->query->get('hostId');

        if (null === $hostId) {
            return new JsonResponse(
                [
                    'code' => 'host_id_not_provided',
                    'message' => 'host id not provided',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

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

        $event = new Event();

        $event
            ->setId(Uuid::v4()->jsonSerialize())
            ->setTitle($title)
            ->setLocation($location)
            ->setDate(new \DateTime($date))
            ->setCategory($category)
            ->setParticipantMax($participantMax)
            ->setCreatedAt(new \DateTime())
            ->setHost($host)
            ->addGuest($host);
        $entityManager->persist($event);

        $dispatcher->dispatch(new EventCreatedEvent($event));

        return new JsonResponse($event->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('v1/api/events/{id}', name: 'v1_api_events_get_by_id', methods: ['GET'])]
    public function getById(
        Event $event
    ): JsonResponse {
        return new JsonResponse($event->jsonSerialize());
    }

    #[Route('v1/api/events', name: 'v1_api_events_get', methods: ['GET'])]
    public function get(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $hostId = $request->query->get('hostId');
        $guestId = $request->query->get('guestId');

        if (null === $hostId && null === $guestId) {
            $events = $entityManager->getRepository(Event::class)->findAll();
            $events = array_map(function (Event $event) {
                return $event->jsonSerialize();
            }, $events);

            return new JsonResponse($events);
        }

        $hostedEvents = [];
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

            $hostedEvents = $entityManager->getRepository(Event::class)->findBy(
                [
                    'host' => $hostId,
                ]
            );
        }

        $guestEvents = [];
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

            $guestEvents = $guest->getEvents()->toArray();
        }

        $events = array_map(function (Event $event) {
            return $event->jsonSerialize();
        }, array_merge($hostedEvents, $guestEvents));

        return new JsonResponse($events);
    }

    #[Route('v1/api/events/{id}', name: 'events_put', methods: ['PUT'])]
    public function put(
        Event $event,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $content = $request->getContent();

        /**
         * @var array{
         *      title: string,
         *      location: string,
         *      date: string,
         *      category: string,
         *      participantMax: int
         *     } $payload */
        $payload = json_decode($content, true);

        $event
            ->setTitle($payload['title'])
            ->setLocation($payload['location'])
            ->setDate(new \DateTime($payload['date']))
            ->setCategory($payload['category'])
            ->setParticipantMax($payload['participantMax']);

        $entityManager->flush();

        return new JsonResponse($event->jsonSerialize());
    }

    #[Route('v1/api/events/{id}', name: 'events_delete', methods: ['DELETE'])]
    public function delete(
        Event $event,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($event);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
