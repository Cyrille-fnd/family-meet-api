<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUser;

use App\Application\QueryHandlerInterface;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class GetUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(GetUserQuery $query): mixed
    {
        return $this->userRepository->get($query->id)->jsonSerialize();
    }
}
