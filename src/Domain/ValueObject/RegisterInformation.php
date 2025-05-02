<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\ValueObject\Identity\UserId;

final readonly class RegisterInformation
{
    public function __construct(
        public UserId $id,
        public string $email,
        public string $password,
        public Sex $sex,
        public string $firstname,
        public string $lastname,
        public ?string $bio,
        public DateTimeImmutable $birthday,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public string $city,
        public ?string $pictureUrl,
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
}
