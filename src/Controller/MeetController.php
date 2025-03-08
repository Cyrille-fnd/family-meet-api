<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Meet;
use App\Entity\User;
use App\Meet\Domain\ValueObject\ChatId;
use App\Meet\Domain\ValueObject\Uuid;
use App\Repository\MeetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MeetController extends AbstractController
{
    #[Route('/api/v2/users/{userId}/meets', name: 'api_v2_meets_post', methods: ['POST'])]
    public function post(
        string $userId,
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $host = $entityManager->getRepository(User::class)->find($userId);

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
        /** @var string $description */
        $description = $payload->get('description');
        /** @var string $location */
        $location = $payload->get('location');
        /** @var string $date */
        $date = $payload->get('date');
        /** @var string $category */
        $category = $payload->get('category');
        /** @var string $maxGuests */
        $maxGuests = $payload->get('participantMax');

        $chat = Chat::create(ChatId::create()->value());
        $chat->addChatter($host);

        $meet = Meet::create(
            id: Uuid::create()->value(),
            title: $title,
            description: $description,
            location: $location,
            date: new \DateTime($date),
            category: $category,
            maxGuests: (int) $maxGuests,
            host: $host,
            chat: $chat,
        );

        $entityManager->persist($meet);
        $entityManager->flush();

        return new JsonResponse($meet->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/api/v2/meets', name: 'api_v2_meets_get', methods: ['GET'])]
    public function get(
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $hostId = $request->query->get('hostId');
        $guestId = $request->query->get('guestId');
        $page = $request->query->get('page') ?? 1;

        if (null === $hostId && null === $guestId) {
            /** @var MeetRepository $repository */
            $repository = $entityManager->getRepository(Meet::class);

            $meets = array_map(function (Meet $meet) {
                return $meet->jsonSerialize();
            }, $repository->findByPage((int) $page));

            return new JsonResponse($meets);
        }

        $meets = [];
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

            $meets['hostedMeets'] = array_map(function (Meet $hostedMeet) {
                return $hostedMeet->jsonSerialize();
            }, $host->getHostedMeets()->toArray());
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

            $meets['meets'] = array_map(function (Meet $meet) {
                return $meet->jsonSerialize();
            }, $guest->getMeets()->toArray());
        }

        return new JsonResponse($meets);
    }

    #[Route('/api/v2/meets/{id}', name: 'api_v2_meets_get_by_id', methods: ['GET'])]
    public function getById(
        string $id,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $meet = $entityManager->getRepository(Meet::class)->find($id);

        if (null === $meet) {
            return new JsonResponse(
                [
                    'code' => 'meet_not_found',
                    'message' => 'meet not found',
                ], Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse($meet->jsonSerialize());
    }

    #[Route('/api/v2/meets/{id}', name: 'api_v2_meets_put', methods: ['PUT'])]
    public function put(
        string $id,
        EntityManagerInterface $entityManager,
        Request $request,
    ): JsonResponse {
        $meet = $entityManager->getRepository(Meet::class)->find($id);

        if (null === $meet) {
            return new JsonResponse(
                [
                    'code' => 'meet_not_found',
                    'message' => 'meet not found',
                ], Response::HTTP_NOT_FOUND);
        }

        $payload = $request->getPayload();

        /** @var string $title */
        $title = $payload->get('title');
        /** @var string $description */
        $description = $payload->get('description');
        /** @var string $location */
        $location = $payload->get('location');
        /** @var string $date */
        $date = $payload->get('date');
        /** @var string $category */
        $category = $payload->get('category');
        /** @var int $maxGuests */
        $maxGuests = $payload->get('participantMax');

        $meet
            ->setTitle($title)
            ->setDescription($description)
            ->setLocation($location)
            ->setDate(new \DateTime($date))
            ->setCategory($category)
            ->setMaxGuests($maxGuests)
            ->setUpdatedAt(new \DateTime());

        $entityManager->flush();

        return new JsonResponse($meet->jsonSerialize());
    }

    #[Route('/api/v2/meets/{id}', name: 'api_v2_meets_delete', methods: ['DELETE'])]
    public function delete(
        string $id,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $meet = $entityManager->getRepository(Meet::class)->find($id);

        if (null === $meet) {
            return new JsonResponse(
                [
                    'code' => 'meet_not_found',
                    'message' => 'meet not found',
                ], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($meet);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
