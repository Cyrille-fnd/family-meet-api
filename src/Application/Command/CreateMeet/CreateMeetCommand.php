<?php

declare(strict_types=1);

namespace App\Application\Command\CreateMeet;

use App\Application\CommandInterface;
use App\Domain\ValueObject\Category;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\MeetId;
use App\Domain\ValueObject\Identity\UserId;

final readonly class CreateMeetCommand implements CommandInterface
{
    public function __construct(
        public MeetId $id,
        public UserId $hostId,
        public string $title,
        public string $description,
        public string $location,
        public DateTimeImmutable $date,
        public Category $category,
        public int $maxGuests,
    ) {
    }
}
