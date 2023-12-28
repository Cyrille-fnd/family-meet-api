<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $location;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $date;

    #[ORM\Column]
    private int $participantMax;

    #[ORM\Column(length: 255)]
    private string $category;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $guests;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(inversedBy: 'hostedEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private User $host;

    #[ORM\OneToOne(inversedBy: 'event', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chat $chat = null;

    public function __construct()
    {
        $this->guests = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getParticipantMax(): int
    {
        return $this->participantMax;
    }

    public function setParticipantMax(int $participantMax): static
    {
        $this->participantMax = $participantMax;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getGuests(): Collection
    {
        return $this->guests;
    }

    public function addGuest(User $guest): static
    {
        if (!$this->guests->contains($guest)) {
            $this->guests->add($guest);
        }

        return $this;
    }

    public function removeGuest(User $guest): static
    {
        $this->guests->removeElement($guest);

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getHost(): User
    {
        return $this->host;
    }

    public function setHost(User $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return array<string, string|int|array<int, array<string, string|null>>|array<string, string|null>>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'location' => $this->getLocation(),
            'date' => $this->getDate()->format('Y-m-d H:i:s'),
            'category' => $this->getCategory(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'participantMax' => $this->getParticipantMax(),
            'guests' => array_map(function (User $user) {
                return $user->jsonSerialize();
            }, $this->getGuests()->toArray()),
            'host' => $this->getHost()->jsonSerialize(),
        ];
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): static
    {
        $this->chat = $chat;

        return $this;
    }
}
