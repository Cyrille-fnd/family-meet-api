<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\QueryInterface;

final readonly class GetUsersQuery implements QueryInterface
{
    public function __construct(public int $page = 1, public int $limit = 10)
    {
    }
}
