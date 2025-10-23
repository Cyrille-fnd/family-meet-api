<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Identity\HostId;

final class Host
{
    public function __construct(
        private HostId $id,
        private User $user,
        private Meet $meet,
    ) {
    }

    public static function create(HostId $id, User $user, Meet $meet): self
    {
        return new self(
            id: $id,
            user: $user,
            meet: $meet,
        );
    }

    public function id(): HostId
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
