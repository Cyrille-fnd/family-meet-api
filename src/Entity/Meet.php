<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MeetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MeetRepository::class)]
class Meet
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $location;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $date;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\ManyToOne(inversedBy: 'hostedMeets')]
    #[ORM\JoinColumn(nullable: false)]
    private User $host;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'meets')]
    private Collection $guests;

    #[ORM\Column(length: 255)]
    private string $category;

    #[ORM\Column]
    private int $maxGuests;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Chat $chat;

    public function __construct(
        string $id,
        string $title,
        string $description,
        string $location,
        \DateTimeInterface $date,
        string $category,
        int $maxGuests,
        User $host,
        Chat $chat,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->date = $date;
        $this->category = $category;
        $this->maxGuests = $maxGuests;
        $this->host = $host;
        $this->chat = $chat;
        $this->guests = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    public static function create(
        string $id,
        string $title,
        string $description,
        string $location,
        \DateTimeInterface $date,
        string $category,
        int $maxGuests,
        User $host,
        Chat $chat,
    ): self {
        return new self(
            id: $id,
            title: $title,
            description: $description,
            location: $location,
            date: $date,
            category: $category,
            maxGuests: $maxGuests,
            host: $host,
            chat: $chat,
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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getMaxGuests(): int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(int $maxGuests): static
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): static
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * @return array<string, array<array<string, string|null>>|array<string, string|null>|string|int|null>
     */
    public function jsonSerialize(): array
    {
        /** @var Uuid $meetId */
        $meetId = $this->getId();

        $guests = array_map(function (User $guest) {
            return $guest->jsonSerialize();
        }, $this->getGuests()->toArray());

        return [
            'id' => $meetId->toRfc4122(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'location' => $this->getLocation(),
            'date' => $this->getDate()->format('Y-m-d H:i:s'),
            'category' => $this->getCategory(),
            'maxGuests' => $this->getMaxGuests(),
            'host' => $this->getHost()->jsonSerialize(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),
            'guests' => $guests,
        ];
    }
}
