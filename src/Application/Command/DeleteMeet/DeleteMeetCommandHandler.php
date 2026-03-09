<?php

declare(strict_types=1);

namespace App\Application\Command\DeleteMeet;

use App\Application\CommandHandlerInterface;
use App\Application\EventDispatcherInterface;
use App\Domain\Event\MeetDeletedEvent;
use App\Domain\Repository\MeetRepositoryInterface;

final readonly class DeleteMeetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private MeetRepositoryInterface $meetRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(DeleteMeetCommand $command): void
    {
        $meet = $this->meetRepository->get($command->id);

        $this->meetRepository->remove($meet);

        $this->eventDispatcher->dispatch(new MeetDeletedEvent($command->id));
    }
}
