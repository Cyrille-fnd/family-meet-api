<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\ValueObject\Identity\UserId;

final readonly class RegisterOutputDto
{
    public function __construct(
        public UserId $userId,
        public string $jwtToken,
    ) {
    }

    public function create(
        UserId $userId,
        string $jwtToken,
    ): self {
        return new self(userId: $userId, jwtToken: $jwtToken);
    }
}
