<?php

declare(strict_types=1);

namespace App\Meet\Anticorruption\Domain\Repository;

use App\Meet\Anticorruption\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function save(User $user): void;
}
