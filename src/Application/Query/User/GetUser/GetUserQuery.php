<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUser;

use App\Application\QueryInterface;
use App\Domain\ValueObject\Identity\UserId;

final readonly class GetUserQuery implements QueryInterface
{
    public function __construct(public UserId $id)
    {
    }
}
