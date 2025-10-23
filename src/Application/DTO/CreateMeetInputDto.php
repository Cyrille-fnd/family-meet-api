<?php

declare(strict_types=1);

namespace App\Application\DTO;

final readonly class CreateMeetInputDto
{
    public function __construct(
        public string $hostId,
        public string $title,
        public string $description,
        public string $location,
        public string $date,
        public string $category,
        public int $maxGuests,
    ) {
    }
}
