<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Meet\Domain\ValueObject\Sex;
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
    #[Route('/api/v2/users', name: 'api_v2_users_get', methods: ['GET'])]
    public function get(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        if ($request->query->get('current')) {
            /** @var User|null $user */
            $user = $this->getUser();

            if (null === $user) {
                return new JsonResponse(
                    [
                        'code' => 'user_not_found',
                        'message' => 'user not found',
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            return new JsonResponse(
                $user->jsonSerialize(),
                Response::HTTP_OK,
                [],
                false
            );
        }

        $users = array_map(function (User $user) {
            return $user->jsonSerialize();
        }, $em->getRepository(User::class)->findAll());

        return new JsonResponse(
            $users,
            Response::HTTP_OK,
            [],
            false
        );
    }

    #[Route('/api/v2/users/{id}', name: 'api_v2_users_get_by_id', methods: ['GET'])]
    public function getById(
        User $user,
    ): JsonResponse {
        return new JsonResponse(
            $user->jsonSerialize(),
            Response::HTTP_OK,
            [],
            false
        );
    }

    #[Route('api/v2/users/{id}', name: 'api_v2_users_put', methods: ['PUT'])]
    public function put(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request,
    ): JsonResponse {
        $content = $request->getContent();

        /**
         * @var array{
         *      sex: string,
         *      firstname: string,
         *      lastname: string,
         *      bio: string|null,
         *      birthday: string,
         *      city: string,
         *      pictureUrl: string|null
         * } $payload
         */
        $payload = json_decode($content, true);

        $user
            ->setSex(Sex::from($payload['sex']))
            ->setFirstname($payload['firstname'])
            ->setLastname($payload['lastname'])
            ->setBio($payload['bio'])
            ->setBirthday(new \DateTime($payload['birthday']))
            ->setCity($payload['city'])
            ->setPictureUrl($payload['pictureUrl']);

        $entityManager->flush();

        return new JsonResponse($user->jsonSerialize());
    }

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
            $user->setPictureUrl(str_replace('amazon_s3', 'localhost',
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
