<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class MessageController extends AbstractController
{
    #[Route('/v1/api/users/{user}/chats/{chat}/messages', name: 'messages_post', methods: ['POST'])]
    public function post(
        User $user,
        Chat $chat,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $content = $request->getContent();

        /**
         * @var array{
         *     content: string
         * } $payload
         */
        $payload = json_decode($content, true);

        $message = new Message();

        $message
            ->setId(Uuid::v4()->jsonSerialize())
            ->setAuthor($user)
            ->setContent($payload['content'])
            ->setChat($chat)
            ->setCreatedAt(new \DateTime());

        $entityManager->persist($message);
        $entityManager->flush();

        return new JsonResponse($message->jsonSerialize(), Response::HTTP_CREATED);
    }
}
