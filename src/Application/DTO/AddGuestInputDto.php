<?php

declare(strict_types=1);

namespace App\Application\DTO;

final readonly class AddGuestInputDto
{
    public function __construct(
        public string $userId,
    ) {
    }
}
