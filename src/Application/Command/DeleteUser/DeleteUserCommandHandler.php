<?php

declare(strict_types=1);

namespace App\Application\Command\DeleteUser;

use App\Application\CommandHandlerInterface;
use App\Application\EventDispatcherInterface;
use App\Domain\Event\UserDeletedEvent;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->userRepository->get($command->id);

        $this->userRepository->remove($user);

        $this->eventDispatcher->dispatch(new UserDeletedEvent($command->id));
    }
}
