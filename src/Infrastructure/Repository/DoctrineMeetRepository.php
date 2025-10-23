<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Meet;
use App\Domain\Repository\MeetRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineMeetRepository implements MeetRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Meet $meet): void
    {
        $this->entityManager->persist($meet);
    }
}
