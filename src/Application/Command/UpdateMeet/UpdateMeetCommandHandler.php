<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateMeet;

use App\Application\CommandHandlerInterface;
use App\Application\EventDispatcherInterface;
use App\Domain\Event\MeetUpdatedEvent;
use App\Domain\Repository\MeetRepositoryInterface;
use App\Domain\ValueObject\DateTimeImmutable;

final readonly class UpdateMeetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private MeetRepositoryInterface $meetRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(UpdateMeetCommand $command): void
    {
        $meet = $this->meetRepository->get($command->id);

        $meet->update(
            $command->title,
            $command->description,
            $command->location,
            $command->date,
            $command->category,
            $command->maxGuests,
            DateTimeImmutable::create(),
        );

        $this->meetRepository->save($meet);

        $this->eventDispatcher->dispatch(new MeetUpdatedEvent($command->id));
    }
}
