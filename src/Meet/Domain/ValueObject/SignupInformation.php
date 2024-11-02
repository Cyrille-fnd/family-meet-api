<?php

declare(strict_types=1);

namespace App\Meet\Domain\ValueObject;

final readonly class SignupInformation
{
    public function __construct(
        public UserId $id,
        public string $email,
        public string $password,
        public string $sex,
        public string $firstName,
        public string $lastName,
        public string $bio,
        public \DateTimeImmutable $birthday,
        public string $city,
        public ?string $pictureUrl,
    ) {
    }

    public static function create(
        UserId $id,
        string $email,
        string $password,
        string $sex,
        string $firstName,
        string $lastName,
        string $bio,
        \DateTimeImmutable $birthday,
        string $city,
        ?string $pictureUrl,
    ): self {
        return new self(
            id: $id,
            email: $email,
            password: $password,
            sex: $sex,
            firstName: $firstName,
            lastName: $lastName,
            bio: $bio,
            birthday: $birthday,
            city: $city,
            pictureUrl: $pictureUrl,
        );
    }
}
