<?php

declare(strict_types=1);

namespace App\Application\Command\AddGuest;

use App\Application\CommandHandlerInterface;
use App\Domain\Entity\Guest;
use App\Domain\Repository\MeetRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Identity\GuestId;

final readonly class AddGuestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private MeetRepositoryInterface $meetRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(AddGuestCommand $command): void
    {
        $meet = $this->meetRepository->get($command->meetId);
        $user = $this->userRepository->get($command->userId);

        foreach ($meet->guests() as $existing) {
            if ($existing->user()->getId()->equals($command->userId)) {
                return;
            }
        }

        $meet->addGuest(Guest::create(GuestId::create(), $user));

        $this->meetRepository->save($meet);
    }
}
