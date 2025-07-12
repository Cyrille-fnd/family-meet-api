<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Identity\UserId;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function get(UserId $id): User
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if (null === $user) {
            throw UserNotFoundException::fromId($id);
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function findAll(int $page = 1, int $limit = 10): array
    {
        return $this->em->getRepository(User::class)->findBy([], null, $limit, ($page - 1) * $limit);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
