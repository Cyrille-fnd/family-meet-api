<?php

namespace App\Dto;

class CreateEventDTO implements \JsonSerializable
{
    /**
     * @param string[] $guests
     */
    public function __construct(
        private string $id,
        private string $title,
        private string $location,
        private string $date,
        private string $category,
        private int $participantMax,
        private \DateTime $createdAt,
        private string $hostId,
        private array $guests = []
    ) {
    }

    /**
     * @param array<string, string|int|array<int, string>> $eventData
     */
    public static function fromArray(array $eventData): self
    {
        /** @var string $id */
        $id = $eventData['id'];
        /** @var string $title */
        $title = $eventData['title'];
        /** @var string $location */
        $location = $eventData['location'];
        /** @var string $date */
        $date = $eventData['date'];
        /** @var string $category */
        $category = $eventData['category'];
        /** @var int $participantMax */
        $participantMax = $eventData['participantMax'];
        /** @var string $createdAt */
        $createdAt = $eventData['createdAt'];
        /** @var string $hostId */
        $hostId = $eventData['hostId'];

        return new self(
            $id,
            $title,
            $location,
            $date,
            $category,
            $participantMax,
            new \DateTime($createdAt),
            $hostId
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getParticipantMax(): int
    {
        return $this->participantMax;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getHostId(): string
    {
        return $this->hostId;
    }

    /**
     * @return string[]
     */
    public function getGuests(): array
    {
        return $this->guests;
    }

    /**
     * @return array<string, string|int|array<int, string>>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'location' => $this->getLocation(),
            'date' => $this->getDate(),
            'category' => $this->getCategory(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'participantMax' => $this->getParticipantMax(),
            'hostId' => $this->getHostId(),
            'guests' => $this->getGuests(),
        ];
    }
}
