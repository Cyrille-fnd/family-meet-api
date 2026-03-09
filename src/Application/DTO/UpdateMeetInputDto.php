<?php

declare(strict_types=1);

namespace App\Application\DTO;

final readonly class UpdateMeetInputDto
{
    public function __construct(
        public string $title,
        public string $description,
        public string $location,
        public string $date,
        public string $category,
        public int $maxGuests,
    ) {
    }
}
