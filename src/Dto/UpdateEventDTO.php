<?php

namespace App\Dto;

class UpdateEventDTO implements \JsonSerializable
{
    public function __construct(
        private ?string $id,
        private ?string $title,
        private ?string $description,
        private ?string $location,
        private ?string $date,
        private ?string $category,
        private ?int $participantMax
    ) {
    }

    /**
     * @param array<string, string|int|null> $eventData
     */
    public static function fromArray(array $eventData): self
    {
        /** @var string $id */
        $id = $eventData['id'];
        /** @var string $title */
        $title = $eventData['title'];
        /** @var string $description */
        $description = $eventData['description'];
        /** @var string $location */
        $location = $eventData['location'];
        /** @var string $date */
        $date = $eventData['date'];
        /** @var string $category */
        $category = $eventData['category'];
        /** @var int $participantMax */
        $participantMax = $eventData['participantMax'];

        return new self(
            $id,
            $title,
            $description,
            $location,
            $date,
            $category,
            $participantMax,
        );
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getParticipantMax(): ?int
    {
        return $this->participantMax;
    }

    /**
     * @return array<string, string|int|null>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'location' => $this->getLocation(),
            'date' => $this->getDate(),
            'category' => $this->getCategory(),
            'participantMax' => $this->getParticipantMax(),
        ];
    }
}
