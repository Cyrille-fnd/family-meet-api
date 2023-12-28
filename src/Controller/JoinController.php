<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class JoinController extends AbstractController
{
    #[Route('/v1/api/users/{user}/events/{event}/join', name: 'events_join', methods: ['POST'])]
    public function join(
        User $user,
        Event $event,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $event->addGuest($user);
        $entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/v1/api/users/{user}/events/{event}/unjoin', name: 'events_unjoin', methods: ['DELETE'])]
    public function unjoin(
        User $user,
        Event $event,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $event->removeGuest($user);
        $entityManager->flush();

        return new JsonResponse();
    }
}
