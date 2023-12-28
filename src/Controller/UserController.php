<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

final class UserController extends AbstractController
{
    #[Route('/v1/api/users', name: 'v1_api_users', methods: ['POST'])]
    public function post(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $JWTTokenManager
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
            ->setPictureUrl($payload['pictureUrl'])
            ->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        $token = $JWTTokenManager->create($user);

        return new JsonResponse(array_merge(['token' => $token], $user->jsonSerialize()),
            Response::HTTP_CREATED
        );
    }

    #[Route('/v1/api/users/{id}', name: 'users_get', methods: ['GET'])]
    public function get(
        User $user
    ): JsonResponse {
        return new JsonResponse(
            $user->jsonSerialize(),
            Response::HTTP_OK,
            [],
            false
        );
    }

    #[Route('v1/api/users/{id}', name: 'users_put', methods: ['PUT'])]
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

    #[Route('/v1/api/users/{id}', name: 'users_delete', methods: ['DELETE'])]
    public function delete(
        User $user,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
