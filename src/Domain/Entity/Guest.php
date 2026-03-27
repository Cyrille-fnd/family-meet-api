<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Identity\GuestId;

class Guest
{
    public function __construct(
        private GuestId $id,
        private User $user,
        private ?Meet $meet = null,
    ) {
    }

    public static function create(GuestId $id, User $user): self
    {
        return new self(id: $id, user: $user);
    }

    public function id(): GuestId
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function meet(): ?Meet
    {
        return $this->meet;
    }

    public function setMeet(Meet $meet): void
    {
        $this->meet = $meet;
    }

    /**
     * @return array<string, string|null>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->user()->getId()->value(),
            'firstname' => $this->user()->getFirstname(),
            'lastname' => $this->user()->getLastname(),
            'sex' => $this->user()->getSex()->value,
            'city' => $this->user()->getCity(),
            'pictureUrl' => $this->user()->getPictureUrl(),
        ];
    }
}
