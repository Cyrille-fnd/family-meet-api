<?php

declare(strict_types=1);

namespace App\Meet\Application\DTO;

use App\Meet\Domain\ValueObject\Identity\UserId;

final readonly class SignupOutputDto
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
