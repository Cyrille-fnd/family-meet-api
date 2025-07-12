<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Entity\User;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class UserController extends AbstractController
{
    #[Route('api/v2/users/{id}/upload', name: 'api_v2_users_upload', methods: ['POST'])]
    public function patch(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request,
        S3Client $client,
    ): JsonResponse {
        /** @var File $file */
        $file = $request->files->get('profilePicture');

        try {
            $client->putObject([
                'Bucket' => $this->getParameter('app.aws_s3_users_bucket_path'),
                'Key' => $user->getId(),
                'SourceFile' => $file,
            ]);

            /** @var Uuid $userId */
            $userId = $user->getId();

            /** @var string $bucketPath */
            $bucketPath = $this->getParameter('app.aws_s3_users_bucket_path');
            $user->setPictureUrl(str_replace(
                'amazon_s3',
                'localhost',
                $client->getObjectUrl(
                    $bucketPath,
                    $userId->toRfc4122()
                )
            ));
            $entityManager->flush();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'code' => 'bad_request',
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([], Response::HTTP_OK);
    }

    #[Route('/api/v2/users/{id}', name: 'api_v2_users_delete', methods: ['DELETE'])]
    public function delete(
        User $user,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
