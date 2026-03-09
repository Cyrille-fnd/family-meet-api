<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateMeet;

use App\Application\CommandInterface;
use App\Domain\ValueObject\Category;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\MeetId;

final readonly class UpdateMeetCommand implements CommandInterface
{
    public function __construct(
        public MeetId $id,
        public string $title,
        public string $description,
        public string $location,
        public DateTimeImmutable $date,
        public Category $category,
        public int $maxGuests,
    ) {
    }
}
