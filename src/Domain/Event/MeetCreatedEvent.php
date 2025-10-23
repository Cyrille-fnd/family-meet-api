<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Application\EventInterface;
use App\Domain\ValueObject\Identity\MeetId;

final readonly class MeetCreatedEvent implements EventInterface
{
    public function __construct(public MeetId $id)
    {
    }
}
