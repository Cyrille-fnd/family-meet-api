<?php

declare(strict_types=1);

namespace App\Application\Command\Register;

use App\Application\CommandHandlerInterface;
use App\Application\EventBusInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\UserCreatorInterface;

final readonly class RegisterCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserCreatorInterface $userCreator,
        private EventBusInterface $bus,
    ) {
    }

    public function __invoke(RegisterCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->registerInformation->email);

        if (null !== $user) {
            throw UserAlreadyExistsException::fromEmail($command->registerInformation->email);
        }

        $user = $this->userCreator->create($command->registerInformation);

        $this->userRepository->save($user);

        $this->bus->dispatch(event: new UserRegisteredEvent($user->getId()));
    }
}
