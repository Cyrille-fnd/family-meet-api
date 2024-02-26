<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ElasticSearch\ElasticaClientGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class JoinController extends AbstractController
{
    #[Route('/v1/api/events/{id}/join', name: 'events_join', methods: ['POST'])]
    public function join(
        string $id,
        EntityManagerInterface $entityManager,
        ElasticaClientGenerator $clientGenerator,
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

        try {
            $client = $clientGenerator->getClient();
            $index = $client->getIndex('familymeet');
            $document = $index->getDocument($id);
            /** @var array<string, array<int, string>|int|string> $event */
            $event = $document->getData();
            /** @var string[] $guests */
            $guests = $event['guests'];

            if (array_key_exists('guests', $event) && in_array($userId, $guests)) {
                return new JsonResponse([
                    'code' => 'user_already_joined',
                    'message' => 'user already joined',
                ], Response::HTTP_BAD_REQUEST);
            }

            $guests[] = $userId;
            $event['guests'] = $guests;
            $document->setData($event);
            $index->updateDocument($document);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'code' => 'bad_request',
                'message' => $exception->getMessage(),
            ]);
        }

        return new JsonResponse();
    }

    #[Route('/v1/api/events/{id}/unjoin', name: 'events_unjoin', methods: ['DELETE'])]
    public function unjoin(
        string $id,
        EntityManagerInterface $entityManager,
        ElasticaClientGenerator $clientGenerator,
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

        try {
            $client = $clientGenerator->getClient();
            $index = $client->getIndex('familymeet');
            $document = $index->getDocument($id);
            /** @var array<string, array<int, string>|int|string> $event */
            $event = $document->getData();
            /** @var string[] $guests */
            $guests = $event['guests'];

            if (!in_array($userId, $guests)) {
                return new JsonResponse([
                    'code' => 'user_not_joined',
                    'message' => 'user is not joined',
                ], Response::HTTP_BAD_REQUEST);
            }

            $guests = array_filter($guests, function (string $value) use ($userId) {
                return $value !== $userId;
            });

            $event['guests'] = array_values($guests);

            $document->setData($event);
            $index->updateDocument($document);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'code' => 'bad_request',
                'message' => $exception->getMessage(),
            ]);
        }

        return new JsonResponse();
    }
}
