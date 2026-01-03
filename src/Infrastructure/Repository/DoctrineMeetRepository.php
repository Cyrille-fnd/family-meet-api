<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Meet;
use App\Domain\Exception\MeetNotFoundException;
use App\Domain\Repository\MeetRepositoryInterface;
use App\Domain\ValueObject\Identity\MeetId;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineMeetRepository implements MeetRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function get(MeetId $id): Meet
    {
        $meet = $this->entityManager->getRepository(Meet::class)->find($id);

        if (null === $meet) {
            throw MeetNotFoundException::fromId($id);
        }

        return $meet;
    }

    public function save(Meet $meet): void
    {
        $this->entityManager->persist($meet);
    }
}
