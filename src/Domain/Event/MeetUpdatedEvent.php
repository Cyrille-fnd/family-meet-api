<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Application\EventInterface;
use App\Domain\ValueObject\Identity\MeetId;

final readonly class MeetUpdatedEvent implements EventInterface
{
    public function __construct(public MeetId $id)
    {
    }
}
