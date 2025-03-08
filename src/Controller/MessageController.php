<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Message;
use App\Entity\User;
use App\Meet\Domain\ValueObject\MessageId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/api/v2/chats/{chatId}/users/{userId}/messages', name: 'api_v2_messages_post', methods: ['POST'])]
    public function post(
        string $chatId,
        string $userId,
        EntityManagerInterface $entityManager,
        Request $request,
    ): JsonResponse {
        $chat = $entityManager->getRepository(Chat::class)->find($chatId);

        if (null === $chat) {
            return new JsonResponse([
                'code' => 'chat_not_found',
                'message' => 'chat not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = $entityManager->getRepository(User::class)->find($userId);

        if (null === $user) {
            return new JsonResponse([
                'code' => 'user_not_found',
                'message' => 'user not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $content = $request->getContent();

        if (!$chat->getChatters()->contains($user)) {
            return new JsonResponse([
                'code' => 'user_not_chat_member',
                'message' => 'user is not a chat member',
            ], Response::HTTP_BAD_REQUEST);
        }

        /**
         * @var array{
         *     content: string
         * } $payload
         */
        $payload = json_decode($content, true);

        $message = new Message(MessageId::create()->value());

        $message
            ->setAuthor($user)
            ->setContent($payload['content'])
            ->setChat($chat);

        $entityManager->persist($message);
        $entityManager->flush();

        return new JsonResponse($message->jsonSerialize(), Response::HTTP_CREATED);
    }
}
