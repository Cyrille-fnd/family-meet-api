<?php

namespace App\Controller;

use App\Entity\Chat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ChatController extends AbstractController
{
    #[Route('/v1/api/chats', name: 'chats_post', methods: ['POST'])]
    public function post(
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $chat = new Chat();

        $chat
            ->setId(Uuid::v4()->jsonSerialize())
            ->setCreatedAt(new \DateTime());

        $entityManager->persist($chat);
        $entityManager->flush();

        return new JsonResponse($chat->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/v1/api/chats/{id}', name: 'chats_get', methods: ['GET'])]
    public function get(
        Chat $chat
    ): JsonResponse {
        return new JsonResponse($chat->jsonSerialize());
    }
}
