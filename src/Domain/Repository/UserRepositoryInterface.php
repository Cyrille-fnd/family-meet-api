<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\ValueObject\Identity\UserId;

interface UserRepositoryInterface
{
    public function get(UserId $id): User;

    /**
     * @return User[]
     */
    public function findAll(int $page = 1, int $limit = 10): array;

    public function findByEmail(string $email): ?User;

    public function save(User $user): void;
}
