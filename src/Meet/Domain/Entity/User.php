<?php

declare(strict_types=1);

namespace App\Meet\Domain\Entity;

class User
{
    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private string $sex,
        private string $firstName,
        private string $lastName,
        private string $bio,
        private \DateTimeImmutable $birthday,
        private string $city,
        private ?string $pictureUrl,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getBio(): string
    {
        return $this->bio;
    }

    public function getBirthday(): \DateTimeImmutable
    {
        return $this->birthday;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }
}
