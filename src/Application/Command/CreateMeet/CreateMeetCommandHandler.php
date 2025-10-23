<?php

declare(strict_types=1);

namespace App\Application\Command\CreateMeet;

use App\Application\CommandHandlerInterface;
use App\Application\EventDispatcherInterface;
use App\Domain\Entity\Host;
use App\Domain\Entity\Meet;
use App\Domain\Event\MeetCreatedEvent;
use App\Domain\Repository\MeetRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\ChatId;
use App\Domain\ValueObject\Identity\HostId;
use App\Entity\Chat;

final readonly class CreateMeetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MeetRepositoryInterface $meetRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(CreateMeetCommand $command): void
    {
        $host = $this->userRepository->get($command->hostId);

        $meet = Meet::create(
            id: $command->id,
            title: $command->title,
            description: $command->description,
            location: $command->location,
            date: $command->date,
            category: $command->category,
            maxGuests: $command->maxGuests,
            createdAt: DateTimeImmutable::create(),
            updatedAt: DateTimeImmutable::create(),
            chat: Chat::create(ChatId::create()->value()),
        );

        $host = Host::create(HostId::create(), $host, $meet);
        $meet->setHost($host);

        $this->meetRepository->save($meet);

        $this->eventDispatcher->dispatch(new MeetCreatedEvent(
            id: $command->id,
        ));
    }
}
