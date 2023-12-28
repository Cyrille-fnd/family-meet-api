<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'chats')]
    private Collection $chatters;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToOne(mappedBy: 'chat', cascade: ['persist', 'remove'])]
    private ?Event $event = null;

    public function __construct()
    {
        $this->chatters = new ArrayCollection();
        $this->messages = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getChatters(): Collection
    {
        return $this->chatters;
    }

    public function addChatter(User $chatter): static
    {
        if (!$this->chatters->contains($chatter)) {
            $this->chatters->add($chatter);
        }

        return $this;
    }

    public function removeChatter(User $chatter): static
    {
        $this->chatters->removeElement($chatter);

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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        $this->messages->removeElement($message);

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): static
    {
        // set the owning side of the relation if necessary
        if ($event->getChat() !== $this) {
            $event->setChat($this);
        }

        $this->event = $event;

        return $this;
    }

    /**
     * @return array<string, string|array<int, array<string, string|array<string, string|null>>|array<string, string|null>>>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'chatters' => array_map(function (User $user) {
                return $user->jsonSerialize();
            }, $this->getChatters()->toArray()),
            'messages' => array_map(function (Message $message) {
                return $message->jsonSerialize();
            }, $this->getMessages()->toArray()),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
