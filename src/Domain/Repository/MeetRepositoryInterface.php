<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Meet;

interface MeetRepositoryInterface
{
    public function save(Meet $meet): void;
}
