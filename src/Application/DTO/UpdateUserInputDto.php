<?php

declare(strict_types=1);

namespace App\Application\DTO;

final readonly class UpdateUserInputDto
{
    public function __construct(
        public string $sex,
        public string $firstname,
        public string $lastname,
        public ?string $bio,
        public string $birthday,
        public string $city,
    ) {
    }
}
