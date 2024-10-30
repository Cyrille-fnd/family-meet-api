<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/api/v2/chats', name: 'api_v2_chats_post', methods: ['POST'])]
    public function post(
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $chat = new Chat();

        $entityManager->persist($chat);
        $entityManager->flush();

        return new JsonResponse($chat->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/api/v2/chats/{id}', name: 'api_v2_chats_get', methods: ['GET'])]
    public function get(
        Chat $chat
    ): JsonResponse {
        return new JsonResponse($chat->jsonSerialize());
    }
}
