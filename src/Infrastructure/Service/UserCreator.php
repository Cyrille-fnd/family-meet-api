<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Entity\User;
use App\Domain\Service\UserCreatorInterface;
use App\Domain\ValueObject\RegisterInformation;
use App\Infrastructure\Bridge\Symfony\Security\SecurityUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserCreator implements UserCreatorInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(RegisterInformation $registerInformation): User
    {
        $user = User::create(
            id: $registerInformation->id,
            email: $registerInformation->email,
            password: $registerInformation->password,
            sex: $registerInformation->sex,
            firstname: $registerInformation->firstname,
            lastname: $registerInformation->lastname,
            bio: $registerInformation->bio,
            birthday: $registerInformation->birthday,
            createdAt: $registerInformation->createdAt,
            updatedAt: $registerInformation->updatedAt,
            city: $registerInformation->city,
            pictureUrl: null,
        );

        $securityUser = new SecurityUser($user);

        $user->setPassword(
            $this->passwordHasher->hashPassword(
                user: $securityUser,
                plainPassword: $securityUser->getPassword()
            )
        );

        return $user;
    }
}
