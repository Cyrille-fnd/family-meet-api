<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Meet;
use App\Domain\ValueObject\Identity\MeetId;

interface MeetRepositoryInterface
{
    public function get(MeetId $id): Meet;

    /** @return Meet[] */
    public function findAll(int $page, int $limit): array;

    public function save(Meet $meet): void;

    public function remove(Meet $meet): void;
}
