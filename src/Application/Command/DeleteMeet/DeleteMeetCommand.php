<?php

declare(strict_types=1);

namespace App\Application\Command\DeleteMeet;

use App\Application\CommandInterface;
use App\Domain\ValueObject\Identity\MeetId;

final readonly class DeleteMeetCommand implements CommandInterface
{
    public function __construct(public MeetId $id)
    {
    }
}
