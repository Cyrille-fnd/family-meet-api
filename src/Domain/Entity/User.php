<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\Sex;

class User implements \JsonSerializable
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        private UserId $id,
        private string $email,
        private string $password,
        private Sex $sex,
        private string $firstname,
        private string $lastname,
        private ?string $bio,
        private DateTimeImmutable $birthday,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private string $city,
        private ?string $pictureUrl,
        private array $roles = ['ROLE_USER'],
    ) {
    }

    public static function create(
        UserId $id,
        string $email,
        string $password,
        Sex $sex,
        string $firstname,
        string $lastname,
        ?string $bio,
        DateTimeImmutable $birthday,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
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
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            city: $city,
            pictureUrl: $pictureUrl,
        );
    }

    public function update(
        Sex $sex,
        string $firstname,
        string $lastname,
        ?string $bio,
        DateTimeImmutable $birthday,
        DateTimeImmutable $updatedAt,
        string $city,
    ): void {
        $this->sex = $sex;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->bio = $bio;
        $this->birthday = $birthday;
        $this->updatedAt = $updatedAt;
        $this->city = $city;
    }

    public function getId(): UserId
    {
        return $this->id;
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
     * @return string[]
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getSex(): Sex
    {
        return $this->sex;
    }

    public function setSex(Sex $sex): static
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

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getBirthday(): DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return array<string, string|null>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId()->value(),
            'email' => $this->getEmail(),
            'sex' => $this->getSex()->value,
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'bio' => $this->getBio(),
            'birthday' => $this->getBirthday()->format('Y-m-d H:i:s'),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'city' => $this->getCity(),
            'pictureUrl' => $this->getPictureUrl(),
        ];
    }
}
