<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Identity\GuestId;

class Guest
{
    public function __construct(
        private GuestId $id,
        private User $user,
        private Meet $meet,
    ) {
    }

    public static function create(GuestId $id, User $user, Meet $meet): self
    {
        return new self(
            id: $id,
            user: $user,
            meet: $meet,
        );
    }

    public function id(): GuestId
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function meet(): Meet
    {
        return $this->meet;
    }
}
