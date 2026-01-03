<?php

declare(strict_types=1);

namespace App\Application\Query\Meet\GetMeet;

use App\Application\QueryHandlerInterface;
use App\Domain\Repository\MeetRepositoryInterface;

final readonly class GetMeetQueryHandler implements QueryHandlerInterface
{
    public function __construct(private MeetRepositoryInterface $meetRepository)
    {
    }

    public function __invoke(GetMeetQuery $query): mixed
    {
        return $this->meetRepository->get(id: $query->id)->jsonSerialize();
    }
}
