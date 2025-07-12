<?php

declare(strict_types=1);

namespace App\Application\Command\DeleteUser;

use App\Application\CommandInterface;
use App\Domain\ValueObject\Identity\UserId;

final readonly class DeleteUserCommand implements CommandInterface
{
    public function __construct(public UserId $id)
    {
    }
}
