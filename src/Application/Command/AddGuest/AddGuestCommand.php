<?php

declare(strict_types=1);

namespace App\Application\Command\AddGuest;

use App\Application\CommandInterface;
use App\Domain\ValueObject\Identity\MeetId;
use App\Domain\ValueObject\Identity\UserId;

final readonly class AddGuestCommand implements CommandInterface
{
    public function __construct(
        public MeetId $meetId,
        public UserId $userId,
    ) {
    }
}
