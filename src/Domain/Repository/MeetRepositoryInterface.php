<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Meet;
use App\Domain\ValueObject\Identity\MeetId;

interface MeetRepositoryInterface
{
    public function get(MeetId $id): Meet;

    public function save(Meet $meet): void;
}
