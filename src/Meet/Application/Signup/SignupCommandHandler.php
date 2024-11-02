<?php

declare(strict_types=1);

namespace App\Meet\Application\Signup;

use App\Meet\Anticorruption\Domain\Repository\UserRepositoryInterface;
use App\Meet\Application\CommandHandlerInterface;
use App\Meet\Domain\Exception\UserAlreadyExistsException;
use App\Meet\Domain\Service\UserCreatorInterface;

final readonly class SignupCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserCreatorInterface $userCreator,
    ) {
    }

    public function __invoke(SignupCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->signupInformation->email);

        if (null !== $user) {
            throw UserAlreadyExistsException::fromEmail($command->signupInformation->email);
        }

        $user = $this->userCreator->create($command->signupInformation);

        $this->userRepository->save($user);
    }
}
