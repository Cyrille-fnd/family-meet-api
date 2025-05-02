<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\ValueObject\Identity\UserId;

class UserNotFoundException extends \Exception
{
    public static function fromId(UserId $id): self
    {
        return new self(\sprintf('Meet with Id <%s> not found', $id->value()));
    }

    public static function fromEmail(string $email): self
    {
        return new self(\sprintf('User with email <%s> not found', $email));
    }
}
