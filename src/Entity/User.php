<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    /**
     * @var string[] $roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $sex;

    #[ORM\Column(length: 255)]
    private string $firstname;

    #[ORM\Column(length: 255)]
    private string $lastname;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $birthday;

    #[ORM\Column(length: 255)]
    private string $city;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pictureUrl;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    /**
     * @var Collection<int, Chat>
     */
    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'chatters')]
    private Collection $chats;

    /**
     * @var Collection<int, Meet>
     */
    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Meet::class)]
    private Collection $hostedMeets;

    /**
     * @var Collection<int, Meet>
     */
    #[ORM\ManyToMany(targetEntity: Meet::class, mappedBy: 'guests')]
    private Collection $meets;

    public function __construct(
        string $id,
        string $email,
        string $password,
        string $sex,
        string $firstname,
        string $lastname,
        string $bio,
        \DateTime $birthday,
        string $city,
        ?string $pictureUrl,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->sex = $sex;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->bio = $bio;
        $this->birthday = $birthday;
        $this->city = $city;
        $this->pictureUrl = $pictureUrl;
        $this->chats = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->roles[] = 'ROLE_USER';
        $this->hostedMeets = new ArrayCollection();
        $this->meets = new ArrayCollection();
    }

    public static function create(
        string $id,
        string $email,
        string $password,
        string $sex,
        string $firstname,
        string $lastname,
        string $bio,
        \DateTime $birthday,
        string $city,
        ?string $pictureUrl,
    ): self {
        return new self(
            id: $id,
            email: $email,
            password: $password,
            sex: $sex,
            firstname: $firstname,
            lastname: $lastname,
            bio: $bio,
            birthday: $birthday,
            city: $city,
            pictureUrl: $pictureUrl,
        );
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setSex(string $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getBirthday(): \DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): static
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
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
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): static
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->addChatter($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            $chat->removeChatter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Meet>
     */
    public function getHostedMeets(): Collection
    {
        return $this->hostedMeets;
    }

    public function addHostedMeet(Meet $hostedMeet): static
    {
        if (!$this->hostedMeets->contains($hostedMeet)) {
            $this->hostedMeets->add($hostedMeet);
            $hostedMeet->setHost($this);
        }

        return $this;
    }

    //    public function removeHostedMeet(Meet $hostedMeet): static
    //    {
    //        if ($this->hostedMeets->removeElement($hostedMeet)) {
    //            // set the owning side to null (unless already changed)
    //            //if ($hostedMeet->getHost() === $this) {
    //                //TODO find a solution here !!!!
    //                //$hostedMeet->setHost(null);
    //            //}
    //        }
    //
    //        return $this;
    //    }

    /**
     * @return Collection<int, Meet>
     */
    public function getMeets(): Collection
    {
        return $this->meets;
    }

    public function addMeet(Meet $meet): static
    {
        if (!$this->meets->contains($meet)) {
            $this->meets->add($meet);
            $meet->addGuest($this);
        }

        return $this;
    }

    public function removeMeet(Meet $meet): static
    {
        if ($this->meets->removeElement($meet)) {
            $meet->removeGuest($this);
        }

        return $this;
    }

    /**
     * @return array<string, string|null>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'sex' => $this->getSex(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'bio' => $this->getBio(),
            'birthday' => $this->getBirthday()->format('Y-m-d H:i:s'),
            'city' => $this->getCity(),
            'pictureUrl' => $this->getPictureUrl(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
