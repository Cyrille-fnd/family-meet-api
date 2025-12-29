<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Category;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\MeetId;
use App\Entity\Chat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Meet
{
    public function __construct(
        private MeetId $id,
        public string $title,
        public string $description,
        public string $location,
        public DateTimeImmutable $date,
        public Category $category,
        public int $maxGuests,
        /**
         * @var Collection<int, Guest>
         */
        private Collection $guests,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private Chat $chat,
        private Host $host,
    ) {
    }

    public static function create(
        MeetId $id,
        string $title,
        string $description,
        string $location,
        DateTimeImmutable $date,
        Category $category,
        int $maxGuests,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        Chat $chat,
        Host $host,
    ): self {
        return new self(
            id: $id,
            title: $title,
            description: $description,
            location: $location,
            date: $date,
            category: $category,
            maxGuests: $maxGuests,
            guests: new ArrayCollection(),
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            chat: $chat,
            host: $host,
        );
    }

    public function id(): MeetId
    {
        return $this->id;
    }

    public function host(): Host
    {
        return $this->host;
    }

    public function setHost(Host $host): void
    {
        $this->host = $host;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function maxGuests(): int
    {
        return $this->maxGuests;
    }

    /**
     * @return Collection<int, Guest>
     */
    public function guests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(Guest $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests->add($guest);
        }

        return $this;
    }

    public function removeGuest(Guest $guest): self
    {
        if ($this->guests->contains($guest)) {
            $this->guests->removeElement($guest);
        }

        return $this;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function chat(): Chat
    {
        return $this->chat;
    }
}
