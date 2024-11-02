<?php

declare(strict_types=1);

namespace App\Meet\Application\DTO;

final readonly class SignupInputDto
{
    public function __construct(
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
}