<?php

declare(strict_types=1);

namespace App\Application\Query\Meet\GetMeet;

use App\Application\QueryInterface;
use App\Domain\ValueObject\Identity\MeetId;

final readonly class GetMeetQuery implements QueryInterface
{
    public function __construct(public MeetId $id)
    {
    }
}
