<?php

declare(strict_types=1);

namespace App\Meet\Domain\Exception;

class UserAlreadyExistsException extends \Exception
{
    public static function fromEmail(string $email): self
    {
        return new self(sprintf('Meet with email <"%s"> already exists', $email));
    }
}
