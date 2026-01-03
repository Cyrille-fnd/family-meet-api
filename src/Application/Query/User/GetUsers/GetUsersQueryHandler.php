<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\QueryHandlerInterface;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;

final readonly class GetUsersQueryHandler implements QueryHandlerInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(GetUsersQuery $query): mixed
    {
        return array_map(static fn (User $user) => $user->jsonSerialize(), $this->userRepository->findAll($query->page, $query->limit));
    }
}
