<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateUser;

use App\Application\CommandInterface;
use App\Domain\ValueObject\DateTimeImmutable;
use App\Domain\ValueObject\Identity\UserId;
use App\Domain\ValueObject\Sex;

final readonly class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        public UserId $id,
        public Sex $sex,
        public string $firstname,
        public string $lastname,
        public ?string $bio,
        public DateTimeImmutable $birthday,
        public DateTimeImmutable $updatedAt,
        public string $city,
    ) {
    }
}
