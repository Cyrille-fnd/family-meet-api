<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class JoinController extends AbstractController
{
    #[Route('/v1/api/events/{event}/join', name: 'events_join', methods: ['POST'])]
    public function join(
        Event $event,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        /** @var string $userId */
        $userId = $request->getPayload()->get('userId');

        $user = $entityManager->getRepository(User::class)->find($userId);

        if (null === $user) {
            return new JsonResponse(
                [
                    'code' => 'user_not_found',
                    'message' => 'user_not_found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $event->addGuest($user);
        $entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/v1/api/events/{event}/unjoin', name: 'events_unjoin', methods: ['DELETE'])]
    public function unjoin(
        Event $event,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        /** @var string $userId */
        $userId = $request->getPayload()->get('userId');

        $user = $entityManager->getRepository(User::class)->find($userId);

        if (null === $user) {
            return new JsonResponse(
                [
                    'code' => 'user_not_found',
                    'message' => 'user_not_found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $event->removeGuest($user);
        $entityManager->flush();

        return new JsonResponse();
    }
}
