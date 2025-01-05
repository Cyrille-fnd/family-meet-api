<?php

declare(strict_types=1);

namespace App\Meet\Domain\Event;

use App\Meet\Application\EventInterface;

final readonly class UserRegisteredEvent implements EventInterface
{
    public function __construct(public string $userId)
    {
    }
}
