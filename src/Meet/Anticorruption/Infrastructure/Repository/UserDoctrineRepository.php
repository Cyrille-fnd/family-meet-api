<?php

declare(strict_types=1);

namespace App\Meet\Anticorruption\Infrastructure\Repository;

use App\Entity\User as legacyUser;
use App\Meet\Anticorruption\Domain\Repository\UserRepositoryInterface;
use App\Meet\Anticorruption\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserDoctrineRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findByEmail(string $email): ?User
    {
        $legacyUser = $this->entityManager->getRepository(legacyUser::class)->findOneBy(['email' => $email]);

        return null != $legacyUser ? User::fromLegacyUser($legacyUser) : null;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user->legacyUser);
    }
}
