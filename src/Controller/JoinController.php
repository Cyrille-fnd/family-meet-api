<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class JoinController extends AbstractController
{
    #[Route('/api/v2/meets/{meetId}/users/{userId}/join', name: 'api_v2_meets_join', methods: ['POST'])]
    public function join(
        string $meetId,
        string $userId,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $meet = $entityManager->getRepository(Meet::class)->find($meetId);

        if (null === $meet) {
            return new JsonResponse(
                [
                    'code' => 'meet_not_found',
                    'message' => 'meet not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $user = $entityManager->getRepository(User::class)->find(Uuid::fromString($userId));

        if (null === $user) {
            return new JsonResponse(
                [
                    'code' => 'user_not_found',
                    'message' => 'user not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $meet->addGuest($user);
        $meet->getChat()->addChatter($user);
        $entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/api/v2/meets/{meetId}/users/{userId}/unjoin', name: 'api_v2_meets_unjoin', methods: ['DELETE'])]
    public function unjoin(
        string $meetId,
        string $userId,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $meet = $entityManager->getRepository(Meet::class)->find($meetId);

        if (null === $meet) {
            return new JsonResponse(
                [
                    'code' => 'meet_not_found',
                    'message' => 'meet not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $user = $entityManager->getRepository(User::class)->find(Uuid::fromString($userId));

        if (null === $user) {
            return new JsonResponse(
                [
                    'code' => 'user_not_found',
                    'message' => 'user not found',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $meet->removeGuest($user);
        $entityManager->flush();

        return new JsonResponse();
    }
}
