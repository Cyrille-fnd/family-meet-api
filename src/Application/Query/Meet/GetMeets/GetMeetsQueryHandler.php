<?php

declare(strict_types=1);

namespace App\Application\Query\Meet\GetMeets;

use App\Application\QueryHandlerInterface;
use App\Domain\Entity\Meet;
use App\Domain\Repository\MeetRepositoryInterface;

final readonly class GetMeetsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private MeetRepositoryInterface $meetRepository)
    {
    }

    public function __invoke(GetMeetsQuery $query): mixed
    {
        return array_map(static fn (Meet $meet) => $meet->jsonSerialize(), $this->meetRepository->findAll($query->page, $query->limit));
    }
}
