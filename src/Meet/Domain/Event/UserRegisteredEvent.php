<?php

declare(strict_types=1);

namespace App\Meet\Domain\Event;

use App\Meet\Domain\ValueObject\UserId;

final readonly class UserRegisteredEvent
{
    public function __construct(
        public UserId $userId,
    ) {
    }
}
