<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Identity\UserId;

interface UserRepositoryInterface
{
    public function get(UserId $id): User;

    public function findByEmail(string $email): ?User;

    public function save(User $user): void;
}
