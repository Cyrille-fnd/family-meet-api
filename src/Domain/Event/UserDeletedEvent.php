<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Application\EventInterface;
use App\Domain\ValueObject\Identity\UserId;

final readonly class UserDeletedEvent implements EventInterface
{
    public function __construct(public UserId $id)
    {
    }
}
