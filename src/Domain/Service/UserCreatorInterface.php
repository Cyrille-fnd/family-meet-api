<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\ValueObject\RegisterInformation;

interface UserCreatorInterface
{
    public function create(RegisterInformation $registerInformation): User;
}
