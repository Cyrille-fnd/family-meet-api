<?php

declare(strict_types=1);

namespace App\Domain\Exception;

final class UserAlreadyExistsException extends \Exception
{
    public static function fromEmail(string $email): self
    {
        return new self(\sprintf('User with email <%s> already exists', $email));
    }
}
