<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateUser;

use App\Application\CommandHandlerInterface;
use App\Application\EventBusInterface;
use App\Domain\Event\UserUpdatedEvent;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventBusInterface $bus,
    ) {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->get($command->id);

        $user->update(
            $command->sex,
            $command->firstname,
            $command->lastname,
            $command->bio,
            $command->birthday,
            $command->updatedAt,
            $command->city
        );

        $this->userRepository->save($user);

        $this->bus->dispatch(event: new UserUpdatedEvent($user->getId()));
    }
}
