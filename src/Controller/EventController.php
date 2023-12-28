<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class EventController extends AbstractController
{
    #[Route('/v1/api/users/{id}/events', name: 'events_post', methods: ['POST'])]
    public function post(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager
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

        $event = new Event();

        $event
            ->setId(Uuid::v4()->jsonSerialize())
            ->setTitle($payload['title'])
            ->setLocation($payload['location'])
            ->setDate(new \DateTime($payload['date']))
            ->setCategory($payload['category'])
            ->setParticipantMax($payload['participantMax'])
            ->setCreatedAt(new \DateTime())
            ->setHost($user);
        $entityManager->persist($event);

        $chat = new Chat();

        $chat
            ->setId(Uuid::v4()->jsonSerialize())
            ->setCreatedAt(new \DateTime())
            ->setEvent($event)
            ->addChatter($user);
        $entityManager->persist($chat);

        $entityManager->flush();

        return new JsonResponse($event->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('v1/api/events/{id}', name: 'events_get', methods: ['GET'])]
    public function get(
        Event $event
    ): JsonResponse {
        return new JsonResponse($event->jsonSerialize());
    }

    #[Route('v1/api/events', name: 'events_get_all', methods: ['GET'])]
    public function getAll(
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $events = $entityManager->getRepository(Event::class)->findAll();
        $events = array_map(function (Event $event) {
            return $event->jsonSerialize();
        }, $events);

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
