<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\ValueObject\Identity\MeetId;

class MeetNotFoundException extends \Exception
{
    public static function fromId(MeetId $id): self
    {
        return new self(\sprintf('Meet with Id <%s> not found', $id->value()));
    }
}
