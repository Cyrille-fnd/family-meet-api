<?php

declare(strict_types=1);

namespace App\Meet\Infrastructure\Service;

use App\Meet\Anticorruption\User;
use App\Meet\Domain\Service\UserCreatorInterface;
use App\Meet\Domain\ValueObject\SignupInformation;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserCreator implements UserCreatorInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(SignupInformation $signupInformation): User
    {
        $user = User::fromUserInformation(userInformation: $signupInformation);

        $user->legacyUser->setPassword(
            $this->passwordHasher->hashPassword(
                user: $user->legacyUser, plainPassword: $user->legacyUser->getPassword()));

        return $user;
    }
}
