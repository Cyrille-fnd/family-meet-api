<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\RegisteredUserEvent;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class UserController extends AbstractController
{
    #[Route('/v1/api/register', name: 'v1_api_users_post', methods: ['POST'])]
    public function post(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTTokenManager,
        MessageBusInterface $bus
    ): JsonResponse {
        $content = $request->getContent();

        /**
         * @var array{
         *      email: string,
         *      password: string,
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

        $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $payload['email'],
        ]);

        if (null !== $user) {
            return new JsonResponse([
                    'code' => 'cannot_create_user',
                    'message' => 'cannot create user',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user
            ->setId(Uuid::v4()->jsonSerialize())
            ->setEmail($payload['email'])
            ->setPassword($passwordHasher->hashPassword($user, $payload['password']))
            ->setRoles(['ROLE_USER'])
            ->setSex($payload['sex'])
            ->setFirstname($payload['firstname'])
            ->setLastname($payload['lastname'])
            ->setBio($payload['bio'])
            ->setBirthday(new \DateTime($payload['birthday']))
            ->setCity($payload['city'])
            ->setPictureUrl(null)
            ->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        $token = $JWTTokenManager->create($user);

        $bus->dispatch(new RegisteredUserEvent($user->getId()));

        return new JsonResponse(array_merge(['token' => $token], $user->jsonSerialize()),
            Response::HTTP_CREATED
        );
    }

    #[Route('/v1/api/users', name: 'v1_api_users_get', methods: ['GET'])]
    public function get(
        Request $request,
        EntityManagerInterface $em
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

    #[Route('/v1/api/users/{id}', name: 'v1_api_users_get_by_id', methods: ['GET'])]
    public function getById(
        User $user
    ): JsonResponse {
        return new JsonResponse(
            $user->jsonSerialize(),
            Response::HTTP_OK,
            [],
            false
        );
    }

    #[Route('v1/api/users/{id}', name: 'v1_api_users_put', methods: ['PUT'])]
    public function put(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request
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
            ->setSex($payload['sex'])
            ->setFirstname($payload['firstname'])
            ->setLastname($payload['lastname'])
            ->setBio($payload['bio'])
            ->setBirthday(new \DateTime($payload['birthday']))
            ->setCity($payload['city'])
            ->setPictureUrl($payload['pictureUrl']);

        $entityManager->flush();

        return new JsonResponse($user->jsonSerialize());
    }

    #[Route('v1/api/users/{id}/upload', name: 'v1_api_users_patch', methods: ['POST'])]
    public function patch(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request,
        S3Client $client
    ): JsonResponse {
        /** @var File $file */
        $file = $request->files->get('profilePicture');

        try {
            $client->putObject([
                'Bucket' => $this->getParameter('app.aws_s3_users_bucket_path'),
                'Key' => $user->getId(),
                'SourceFile' => $file,
            ]);

            /** @var string $bucketPath */
            $bucketPath = $this->getParameter('app.aws_s3_users_bucket_path');
            $user->setPictureUrl(str_replace('amazon_s3', 'localhost',
                $client->getObjectUrl(
                    $bucketPath,
                    $user->getId()
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

    #[Route('/v1/api/users/{id}', name: 'v1_api_users_delete', methods: ['DELETE'])]
    public function delete(
        User $user,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
