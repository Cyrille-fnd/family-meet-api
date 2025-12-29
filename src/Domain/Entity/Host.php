<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Identity\HostId;

final class Host
{
    public function __construct(
        private HostId $id,
        private User $user,
    ) {
    }

    public static function create(HostId $id, User $user): self
    {
        return new self(
            id: $id,
            user: $user,
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
}
